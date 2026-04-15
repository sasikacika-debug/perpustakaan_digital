@extends('layouts.app')

@section('title', 'Struk Pembayaran Denda')

@section('content')
    @php
        $receiptNumber = 'STRUK-' . str_pad((string) $loan->id, 6, '0', STR_PAD_LEFT);
    @endphp

    <style>
        .receipt-wrapper {
            max-width: 820px;
            margin: 0 auto;
        }

        .receipt-sheet {
            background: #ffffff;
            border: 1px solid #dbe3ef;
            border-radius: 24px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
            overflow: hidden;
        }

        .receipt-header {
            background: linear-gradient(135deg, #0f172a, #1d4ed8);
            color: #fff;
            padding: 2rem;
        }

        .receipt-kicker {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            font-size: 0.8rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .receipt-grid {
            display: grid;
            grid-template-columns: 1.3fr 0.9fr;
            gap: 1.5rem;
            padding: 2rem;
        }

        .receipt-panel {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 1.25rem 1.35rem;
            background: #fff;
        }

        .receipt-meta {
            display: grid;
            grid-template-columns: 170px 1fr;
            gap: 0.75rem 1rem;
            margin: 0;
        }

        .receipt-meta dt {
            color: #64748b;
            font-weight: 600;
        }

        .receipt-meta dd {
            margin: 0;
            color: #0f172a;
            font-weight: 500;
        }

        .receipt-amount {
            background: linear-gradient(180deg, #eff6ff, #dbeafe);
            border: 1px solid #bfdbfe;
        }

        .receipt-amount-value {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }

        .receipt-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.8rem;
            border-radius: 999px;
            background: #dcfce7;
            color: #166534;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .receipt-notes {
            padding: 0 2rem 2rem;
        }

        .receipt-notes-box {
            border-top: 1px dashed #cbd5e1;
            padding-top: 1rem;
            color: #475569;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 12mm;
            }

            .receipt-wrapper {
                max-width: 100%;
            }

            .receipt-sheet {
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
                border-radius: 0 !important;
            }

            .receipt-header {
                background: #fff !important;
                color: #000 !important;
                border-bottom: 2px solid #0f172a;
            }

            .receipt-kicker {
                background: #f1f5f9 !important;
                color: #0f172a !important;
                border: 1px solid #cbd5e1;
            }

            .receipt-grid {
                grid-template-columns: 1fr 0.9fr;
                gap: 1rem;
                padding: 1.25rem;
            }

            .receipt-panel,
            .receipt-amount {
                break-inside: avoid;
                box-shadow: none !important;
            }

            .receipt-notes {
                padding: 0 1.25rem 1.25rem;
            }
        }

        @media (max-width: 768px) {
            .receipt-grid {
                grid-template-columns: 1fr;
            }

            .receipt-meta {
                grid-template-columns: 1fr;
                gap: 0.35rem;
            }
        }
    </style>

    <div class="receipt-wrapper">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h4 class="mb-2">Struk Pembayaran Denda</h4>
                <p class="text-muted mb-0">Tampilan struk sudah disiapkan agar rapi di layar dan tetap bersih saat dicetak.</p>
            </div>
            <div class="no-print">
                <button type="button" class="btn btn-primary" onclick="window.print()">Cetak Struk</button>
            </div>
        </div>

        <div class="receipt-sheet">
            <div class="receipt-header">
                <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                    <div>
                        <span class="receipt-kicker">E-Perpus</span>
                        <h2 class="h3 mt-3 mb-2">Struk Pembayaran Denda</h2>
                        <p class="mb-0 opacity-75">Bukti resmi pembayaran denda anggota perpustakaan.</p>
                    </div>
                    <div class="text-md-end">
                        <div class="small text-white-50">Nomor Struk</div>
                        <div class="fs-5 fw-bold">{{ $receiptNumber }}</div>
                        <div class="small mt-2">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <div class="receipt-grid">
                <div class="receipt-panel">
                    <h5 class="mb-3">Detail Transaksi</h5>
                    <dl class="receipt-meta">
                        <dt>Nama Anggota</dt>
                        <dd>{{ $loan->user->name }}</dd>

                        <dt>Judul Buku</dt>
                        <dd>{{ $loan->book->title }}</dd>

                        <dt>Tanggal Pinjam</dt>
                        <dd>{{ optional($loan->borrowed_at)->format('d F Y') ?: '-' }}</dd>

                        <dt>Tanggal Kembali</dt>
                        <dd>{{ optional($loan->returned_at)->format('d F Y') ?: '-' }}</dd>

                        <dt>Status Pembayaran</dt>
                        <dd><span class="receipt-badge">{{ $loan->fine_status === 'confirmed' ? 'Sudah Dibayar' : 'Belum Dibayar' }}</span></dd>

                        <dt>Petugas</dt>
                        <dd>{{ auth()->user()->name }}</dd>
                    </dl>
                </div>

                <div class="receipt-panel receipt-amount">
                    <div class="text-uppercase small fw-semibold text-primary mb-2">Total Pembayaran</div>
                    <div class="receipt-amount-value mb-3">Rp{{ number_format($loan->fine, 0, ',', '.') }}</div>
                    <div class="small text-muted mb-3">Nominal denda yang telah dikonfirmasi pada transaksi ini.</div>
                    <div class="border-top pt-3">
                        <div class="small text-muted">ID Transaksi</div>
                        <div class="fw-semibold">#{{ $loan->id }}</div>
                    </div>
                </div>
            </div>

            <div class="receipt-notes">
                <div class="receipt-notes-box">
                    <div class="fw-semibold mb-2">Catatan</div>
                    <p class="mb-1">Simpan struk ini sebagai bukti pembayaran denda perpustakaan.</p>
                    <p class="mb-0">Jika ada ketidaksesuaian data, silakan hubungi petugas perpustakaan dengan menunjukkan nomor struk ini.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
