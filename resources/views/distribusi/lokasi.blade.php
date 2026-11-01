@extends('layouts.app')
@section('title', 'Pilih Lokasi Tayang')
@section('page-title', 'Iklan > Distribusi > Pilih Lokasi Tayang')

@section('content')

<style>
    .vortex-card-index { background: var(--bg-card, #ffffff); border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); border: 1px solid var(--border-main, #e9ecef); overflow: hidden; width: 100%; margin-top: 10px; }
    .lokasi-info { padding: 20px 24px; border-bottom: 1px solid var(--border-main, #e9ecef); display: flex; gap: 30px; flex-wrap: wrap; }
    .lokasi-info .lbl { font-size: 12px; font-weight: 800; color: var(--text-muted, #7f8c8d); text-transform: uppercase; letter-spacing: .8px; margin-bottom: 4px; }
    .lokasi-info .val { font-weight: 700; color: var(--text-main, #2c3e50); font-size: 14px; }
    .lokasi-toolbar { padding: 16px 24px; display: flex; gap: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap; }
    .table-vortex { width: 100% !important; border-collapse: collapse !important; margin: 0 !important; }
    .table-vortex th { background-color: var(--bg-table-th, #f8f9fa) !important; color: var(--text-main, #2c3e50) !important; font-weight: 800 !important; border-bottom: 2px solid var(--border-main, #e9ecef) !important; text-transform: uppercase !important; font-size: 0.75rem !important; letter-spacing: 0.8px !important; padding: 14px 16px !important; vertical-align: middle !important; }
    .table-vortex td { padding: 12px 16px !important; border-bottom: 1px solid var(--border-table, #cbd5e1) !important; color: var(--text-main, #2c3e50) !important; font-weight: 600 !important; font-size: 0.85rem !important; }
    .table-vortex tbody tr:hover td { background-color: rgba(114, 200, 101, 0.05) !important; cursor: pointer; }
    .table-vortex input[type=checkbox] { transform: scale(1.25); cursor: pointer; }
    .vortex-btn-locked { padding: 12px 24px; border-radius: 8px; font-weight: 800; font-size: 13px; text-transform: uppercase; border: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; text-decoration: none; }
    .btn-save-v { background: #2c3e50; color: #fff; box-shadow: 0 4px 10px rgba(44,62,80,.2); }
    .btn-back-v { background: #b2bec3; color: #fff; }
    .btn-cancel-v { background: #e74c3c; color: #fff; }
    .badge-jam-sama { background: rgba(231, 76, 60, 0.15); color: #c62828; padding: 3px 8px; border-radius: 6px; font-weight: 800; font-size: .72rem; border: 1px solid rgba(231, 76, 60, 0.4); display: inline-block; }
    .badge-jam-beda { background: rgba(54, 185, 204, 0.12); color: #0e7490; padding: 3px 8px; border-radius: 6px; font-weight: 700; font-size: .72rem; display: inline-block; }
    .badge-draft-item { background: rgba(44, 62, 80, 0.08); border: 1px solid #cbd5e1; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 700; color: #2c3e50; display: inline-flex; align-items: center; gap: 6px; margin: 2px 4px 2px 0; }
</style>

<div class="vortex-card-index">
    {{-- Header Rincian Batch yang Akan Didistribusikan --}}
    <div class="lokasi-info">
        <div style="flex: 1; min-width: 250px;">
            <div class="lbl">Periode Distribusi</div>
            <div class="val">
                <i class="far fa-calendar-alt text-primary mr-1"></i>
                {{ \Carbon\Carbon::parse($draft['tanggal_mulai'])->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($draft['tanggal_selesai'])->format('d-m-Y') }}
                <span style="color: var(--text-muted); font-size: 12px;">({{ $draft['frekuensi_hari'] }})</span>
            </div>
        </div>

        <div style="flex: 2; min-width: 320px;">
            <div class="lbl">Daftar Jadwal & Jam Tayang yang Didistribusikan Sekaligus ({{ count($draft['items']) }} Jadwal)</div>
            <div style="display: flex; flex-wrap: wrap; margin-top: 4px;">
                @foreach($draft['items'] as $idx => $it)
                    @php
                        $jObj = $jadwalMap[$it['jadwal_tayang_id']] ?? null;
                        $jNama = $jObj?->nama_jadwal ?? 'Jadwal #' . $it['jadwal_tayang_id'];
                    @endphp
                    <span class="badge-draft-item">
                        <strong>#{{ sprintf('%02d', $idx + 1) }}:</strong> {{ $jNama }}
                        <span style="color:#e67e22; font-weight:800;">[{{ substr($it['waktu_mulai'], 0, 5) }} - {{ substr($it['waktu_selesai'], 0, 5) }}]</span>
                    </span>
                @endforeach
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('distribusi.lokasi.simpan') }}" id="formLokasi">
        @csrf

        @if($errors->any())
            <div style="background:#fdecea;color:#c62828;padding:14px 18px;margin:16px 24px 0;border-radius:8px;font-weight:700;font-size:13px;border-left:4px solid #e74c3c;">
                @foreach($errors->all() as $e)<div><i class="fas fa-exclamation-circle mr-1"></i> {{ $e }}</div>@endforeach
            </div>
        @endif

        <div class="lokasi-toolbar">
            <div style="font-weight:800;color:var(--text-main,#2c3e50);">Pilih Lokasi Tayang (Signage)
                <span id="counter" style="font-weight:600;color:var(--text-muted,#7f8c8d);margin-left:8px;">0 dipilih</span>
            </div>
            <input type="text" id="filter" class="form-control" placeholder="Cari toko / signage / kota..." style="max-width:320px;">
        </div>

        @php
            $targetJamKeys = collect($draft['items'])->map(fn ($it) => substr($it['waktu_mulai'], 0, 5) . '-' . substr($it['waktu_selesai'], 0, 5))->all();
        @endphp

        <div class="table-responsive">
            <table class="table-vortex">
                <thead>
                    <tr>
                        <th style="width:4%;text-align:center;"><input type="checkbox" id="checkAll" title="Pilih semua"></th>
                        <th>Kode Toko</th>
                        <th>Nama Toko</th>
                        <th>Provinsi / Kota</th>
                        <th>Label / Nama Signage</th>
                        <th>Kode Signage</th>
                        <th>Jadwal Saat Ini & Jam Tayang</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($signage as $s)
                    @php $sekarang = $s->distribusiJadwal; @endphp
                    <tr>
                        <td style="text-align:center;"><input type="checkbox" class="chk" name="signage_ids[]" value="{{ $s->id }}" {{ in_array($s->id, old('signage_ids', [])) ? 'checked' : '' }}></td>
                        <td>{{ $s->toko?->kode_toko ?? '-' }}</td>
                        <td>{{ strtoupper($s->toko?->nama_toko ?? 'Tanpa Toko') }}</td>
                        <td>{{ strtoupper(($s->toko?->provinsi ?? '-') . ' > ' . ($s->toko?->kota ?? '-')) }}</td>
                        <td>{{ strtoupper($s->nama_signage) }}</td>
                        <td>{{ $s->device_id }}</td>
                        <td>
                            @forelse($sekarang as $dj)
                                @php
                                    $jamDj = substr($dj->waktu_mulai, 0, 5) . ' - ' . substr($dj->waktu_selesai, 0, 5);
                                    $keyDj = substr($dj->waktu_mulai, 0, 5) . '-' . substr($dj->waktu_selesai, 0, 5);
                                    $isSameTime = in_array($keyDj, $targetJamKeys);
                                @endphp
                                <div style="margin-bottom: 4px;">
                                    @if($isSameTime)
                                        <span class="badge-jam-sama" title="Format jam ini sama persis dengan yang ada di draft (akan diperbarui jika dicentang timpa)">
                                            <i class="fas fa-exclamation-triangle"></i> {{ $dj->jadwalTayang?->nama_jadwal ?? '-' }} [{{ $jamDj }}]
                                        </span>
                                    @else
                                        <span class="badge-jam-beda" title="Jadwal ini tetap aktif di jamnya">
                                            <i class="far fa-clock mr-1"></i> {{ $dj->jadwalTayang?->nama_jadwal ?? '-' }} [{{ $jamDj }}]
                                        </span>
                                    @endif
                                </div>
                            @empty
                                <span style="color:var(--text-muted,#7f8c8d);">-</span>
                            @endforelse
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted,#7f8c8d);">Belum ada signage terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding:16px 24px;background:rgba(54,185,204,0.06);border-top:1px solid var(--border-main,#e9ecef);font-size:13px;color:var(--text-main,#2c3e50);">
            <div style="font-weight:800;margin-bottom:6px;"><i class="fas fa-info-circle text-info mr-1"></i> Aturan Multi-Jadwal & Jam Tayang (Dayparting):</div>
            <ul style="margin:0;padding-left:20px;line-height:1.6;">
                <li>Seluruh jadwal tayang di atas akan <strong>didistribusikan sekalian</strong> ke signage terpilih.</li>
                <li>Jadwal default harian (format utama <strong>00:00 - 23:59</strong>) dan jadwal promo khusus di jam lain (misal: <strong>11:00 - 12:00</strong>) berjalan berdampingan pada perangkat TV.</li>
                <li>Distribusi <strong>tidak bisa di-push</strong> jika ada format jam yang bentrok dengan jadwal yang sudah ada di signage, kecuali dicentang opsi perbarui di bawah ini.</li>
            </ul>
            <div style="margin-top:12px;padding-top:10px;border-top:1px dashed #cbd5e1;">
                <label style="cursor:pointer;font-weight:800;color:#c62828;">
                    <input type="checkbox" name="timpa_jam_sama" value="1" {{ old('timpa_jam_sama') ? 'checked' : '' }} style="transform:scale(1.25);margin-right:8px;">
                    Timpa jadwal lama jika memiliki format jam yang sama persis
                </label>
            </div>
        </div>

        <div style="display:flex;gap:12px;padding:24px;border-top:1px solid var(--border-main,#e9ecef);">
            <a href="{{ route('distribusi.create') }}" class="vortex-btn-locked btn-back-v"><i class="fas fa-arrow-left"></i> Kembali</a>
            <button type="submit" class="vortex-btn-locked btn-save-v"><i class="fas fa-paper-plane"></i> Lanjut Push ke TV</button>
            <a href="{{ route('distribusi.batal') }}" class="vortex-btn-locked btn-cancel-v"><i class="fas fa-times"></i> Batal</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkAll');
    const rows     = Array.from(document.querySelectorAll('.table-vortex tbody tr'));
    const counter  = document.getElementById('counter');
    const visible  = () => rows.filter(r => r.style.display !== 'none').map(r => r.querySelector('.chk')).filter(Boolean);

    function update() {
        const all = document.querySelectorAll('.chk');
        counter.textContent = Array.from(all).filter(c => c.checked).length + ' dipilih';
        const v = visible();
        checkAll.checked = v.length > 0 && v.every(c => c.checked);
    }
    checkAll.addEventListener('change', () => { visible().forEach(c => c.checked = checkAll.checked); update(); });
    document.querySelectorAll('.chk').forEach(c => c.addEventListener('change', update));
    rows.forEach(r => r.addEventListener('click', e => {
        const c = r.querySelector('.chk');
        if (c && e.target !== c && !e.target.closest('a') && !e.target.closest('button')) {
            c.checked = !c.checked;
            update();
        }
    }));
    document.getElementById('filter').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        rows.forEach(r => r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none');
        update();
    });
    update();
});
</script>
@endsection