<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Indikator;
use App\Models\Kompetensi;
use App\Models\QuestionSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class QuestionSetController extends Controller
{
    public function create()
    {
        return view('admin.paket_soal.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:question_sets',
            'time_limit' => 'required|integer',
            'start_exam' => 'required|date_format:Y-m-d\TH:i',
            'end_exam' => 'required|date_format:Y-m-d\TH:i|after:start_exam',
            'role' => 'required|in:Guru,Kepala Sekolah',
            'import_soal' => 'nullable|file|mimes:xlsx,xls,csv'
        ], [
            'name.unique' => 'Nama paket soal sudah terpakai.',
        ]);

        DB::beginTransaction();
        try {
            $questionSet = QuestionSet::create([
                'name' => $request->name,
                'time_limit' => $request->time_limit,
                'start_exam' => $request->start_exam,
                'end_exam' => $request->end_exam,
                'role' => $request->role,
            ]);

            if ($request->hasFile('import_soal')) {
                $rows = Excel::toArray([], $request->file('import_soal'))[0]; // Sheet pertama

                // Validasi format file Excel
                $expectedHeader = ['soal', 'kompetensi', 'indikator', 'jawaban_1', 'bobot_1', 'jawaban_2', 'bobot_2', 'jawaban_3', 'bobot_3', 'jawaban_4', 'bobot_4'];
                $header = array_map('strtolower', $rows[0] ?? []);
                $isValidHeader = true;
                foreach ($expectedHeader as $i => $col) {
                    if (!isset($header[$i]) || strpos($header[$i], $col) === false) {
                        $isValidHeader = false;
                        break;
                    }
                }
                if (!$isValidHeader) {
                    DB::rollBack();
                    return back()->withErrors(['import_soal' => 'Format file Excel tidak sesuai. Pastikan urutan dan nama kolom: soal, kompetensi, indikator, jawaban_1, bobot_1, dst.']);
                }

                // Cek soal ganda di file excel
                $soalList = [];
                foreach ($rows as $i => $row) {
                    if ($i == 0) continue; // Lewati header
                    $soalText = trim($row[0] ?? '');
                    if (empty($soalText)) continue;
                    if (in_array(strtolower($soalText), $soalList)) {
                        DB::rollBack();
                        return back()->withErrors(['import_soal' => "Terdapat soal ganda di file Excel pada baris ke-" . ($i + 1) . ": \"$soalText\""]);
                    }
                    $soalList[] = strtolower($soalText);
                }

                // Validasi dan simpan data
                foreach ($rows as $i => $row) {
                    if ($i == 0) continue; // Lewati header

                    // Validasi wajib isi
                    if (empty($row[0]) || empty($row[1]) || empty($row[2])) {
                        DB::rollBack();
                        return back()->withErrors(['import_soal' => "Baris ke-" . ($i + 1) . " tidak lengkap. Semua kolom soal, kompetensi, indikator wajib diisi."]);
                    }

                    // Validasi jawaban dan skor
                    $hasAnswer = false;
                    $scoreList = [];
                    for ($j = 3; $j < count($row); $j += 2) {
                        if (!empty($row[$j])) {
                            $hasAnswer = true;
                            $score = intval($row[$j + 1] ?? 0);
                            if ($score < 1) {
                                DB::rollBack();
                                return back()->withErrors(['import_soal' => "Score jawaban pada baris ke-" . ($i + 1) . " minimal 1."]);
                            }
                            if (in_array($score, $scoreList)) {
                                DB::rollBack();
                                return back()->withErrors(['import_soal' => "Terdapat duplikasi bobot ($score) pada baris ke-" . ($i + 1) . ". Setiap bobot jawaban harus unik dalam satu soal."]);
                            }
                            $scoreList[] = $score;
                        }
                    }
                    if (!$hasAnswer) {
                        DB::rollBack();
                        return back()->withErrors(['import_soal' => "Baris ke-" . ($i + 1) . " harus memiliki minimal satu jawaban."]);
                    }

                    // Ambil atau buat kompetensi
                    $kompetensi = Kompetensi::firstOrCreate(['nama' => $row[1]]);
                    // Ambil atau buat indikator (harus ada kompetensi_id)
                    $indikator = Indikator::firstOrCreate([
                        'nama' => $row[2],
                        'kompetensi_id' => $kompetensi->id,
                    ]);

                    $question = Question::create([
                        'question_text' => $row[0],
                        'question_set_id' => $questionSet->id,
                        'kompetensi_id' => $kompetensi->id,
                        'indikator_id' => $indikator->id,
                    ]);

                    // Jawaban mulai dari kolom ke-3 (index 3), format: answer, score, answer, score, dst
                    for ($j = 3; $j < count($row); $j += 2) {
                        if (!empty($row[$j])) {
                            Answer::create([
                                'question_id' => $question->id,
                                'answer_text' => $row[$j],
                                'score' => intval($row[$j + 1] ?? 1),
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.soal')->with('success', 'Paket Soal berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['import_soal' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function index()
    {
        $questionSets = QuestionSet::all();
        return view('admin.paket_soal.index', compact('questionSets'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input dari form
        $request->validate([
            'name' => 'required|string|max:255|unique:question_sets,name,' . $id,
            'time_limit' => 'required|integer',
            'start_exam' => 'required|date_format:Y-m-d\TH:i',
            'end_exam' => 'required|date_format:Y-m-d\TH:i|after:start_exam',
            'role' => 'required|in:Guru,Kepala Sekolah',
        ], [
            'name.unique' => 'Nama paket soal sudah terpakai.',
        ]);

        // Cari QuestionSet berdasarkan ID
        $questionSet = QuestionSet::findOrFail($id);

        // Update data QuestionSet
        $questionSet->update([
            'name' => $request->name,
            'time_limit' => $request->time_limit,
            'start_exam' => $request->start_exam,
            'end_exam' => $request->end_exam,
            'role' => $request->role,
        ]);

        // Redirect kembali ke halaman list soal dengan pesan sukses
        return redirect()->route('admin.soal')->with('success', 'Paket Soal berhasil diperbarui');
    }

    public function destroy($id)
    {
        $questionSet = QuestionSet::findOrFail($id);

        $questionSet->delete();

        return redirect()->route('admin.soal')->with('success', 'Paket Soal berhasil dihapus');
    }
}
