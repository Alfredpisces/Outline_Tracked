@php
    /**
     * Printable certificate template (HTML-first).
     */
    $studentName = $student->full_name ?? trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
    $lrn = $student->lrn ?? '—';
    $grade = $student->grade_level ?? '—';
    $section = $student->section ?? '';
    $schoolYear = $student->school_year ?? '';
    $issuedDate = now()->format('F d, Y');

    // Identifiers
    $identifier = $student->id ?? substr($lrn, -6);
    $certificateNo = 'GM-' . now()->format('Y') . '-' . str_pad((int) $identifier, 6, '0', STR_PAD_LEFT);

    // Format Meta
    $sectionLabel = trim((string) $section) !== '' ? "Section $section" : '';
    $gradeLabel = $grade !== '—' ? "Grade $grade" : '';
    $metaLine = trim(
        collect([$gradeLabel, $sectionLabel, $schoolYear ? "SY $schoolYear" : null])
            ->filter()
            ->implode(' • '),
    );

    // Dynamic Settings
    $schoolName = \App\Models\Setting::get('school_name', 'TrackEd Systems High School');
    $principalName = \App\Models\Setting::get('principal_name', 'School Principal');
    $counselorName = Auth::user() ? Auth::user()->first_name . ' ' . Auth::user()->last_name : 'Guidance Counselor';
    $hasCustomLogo = \App\Models\Setting::get('has_custom_logo') === 'true';
@endphp

<section class="certificate">
    <div class="paper">
        <header class="topbar">
            <div class="brand">
                <div class="seal">
                    @if ($hasCustomLogo)
                        <img src="{{ asset('images/school-logo.png') }}" alt="School Seal"
                            style="width: 100%; height: 100%; object-fit: contain; padding: 4px;">
                    @else
                        TE
                    @endif
                </div>
                <div class="brand-text">
                    <div class="brand-name">{{ $schoolName }}</div>
                    <div class="brand-sub">TrackEd System • Good Moral Certificate</div>
                </div>
            </div>

            <div class="meta">
                <div class="meta-row">
                    <span class="meta-label">Certificate No.</span>
                    <span class="meta-value">{{ $certificateNo }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Issued Date</span>
                    <span class="meta-value">{{ $issuedDate }}</span>
                </div>
                @if ($metaLine !== '')
                    <div class="meta-row">
                        <span class="meta-label">Student Info</span>
                        <span class="meta-value">{{ $metaLine }}</span>
                    </div>
                @endif
            </div>
        </header>

        <main class="body">
            <div class="title-wrap">
                <h1 class="title">Certificate of Good Moral Character</h1>
                <p class="subtitle">
                    This certifies that the student named below is of good moral standing based on available
                    disciplinary records.
                </p>
            </div>

            <div class="recipient">
                <div class="field-label">This is to certify that</div>
                <div class="field-value">{{ $studentName ?: $lrn }}</div>

                <div class="grid">
                    <div class="pill">
                        <div class="pill-label">LRN</div>
                        <div class="pill-value">{{ $lrn }}</div>
                    </div>

                    <div class="pill">
                        <div class="pill-label">Grade Level</div>
                        <div class="pill-value">{{ $grade }}</div>
                    </div>

                    <div class="pill">
                        <div class="pill-label">Section</div>
                        <div class="pill-value">{{ $section !== '' ? $section : '—' }}</div>
                    </div>
                </div>
            </div>

            <div class="statement">
                <p>
                    Based on the official records of <strong>{{ $schoolName }}</strong>, the aforementioned student
                    has <strong>no unresolved major or critical disciplinary infractions</strong> on file as of the date
                    issued.
                </p>
                <p class="small">
                    This certificate is issued upon request for clearance and other lawful purposes, subject to
                    verification and school policies.
                </p>
            </div>

            <div class="signatures">
                <div class="sig">
                    <div class="sig-line"></div>
                    <div class="sig-name">{{ strtoupper($counselorName) }}</div>
                    <div class="sig-role">Guidance Counselor</div>
                </div>

                <div class="sig">
                    <div class="sig-line"></div>
                    <div class="sig-name">{{ strtoupper($principalName) }}</div>
                    <div class="sig-role">School Head / Approving Authority</div>
                </div>
            </div>
        </main>

        <footer class="footer">
            <div class="note">
                <strong>Note:</strong> Any alteration or unauthorized reproduction invalidates this certificate.
            </div>
            <div class="foot-meta">
                Generated via TrackEd • PBAC-enabled • {{ now()->format('Y-m-d H:i') }}
            </div>
        </footer>
    </div>
</section>

<style>
    .certificate {
        width: 100%;
        margin-bottom: 20mm;
    }

    .paper {
        width: 100%;
        min-height: 257mm;
        padding: 18mm;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #ffffff;
        position: relative;
        box-sizing: border-box;
        overflow: hidden;
        page-break-after: always;
    }

    .paper::before {
        content: "";
        position: absolute;
        inset: -80px -80px auto auto;
        width: 240px;
        height: 240px;
        background: radial-gradient(circle at 30% 30%, rgba(16, 185, 129, 0.22), rgba(99, 102, 241, 0.08), transparent 70%);
        filter: blur(0px);
        transform: rotate(8deg);
        pointer-events: none;
    }

    .paper::after {
        content: "";
        position: absolute;
        inset: auto auto -120px -120px;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle at 40% 40%, rgba(99, 102, 241, 0.18), rgba(16, 185, 129, 0.08), transparent 70%);
        transform: rotate(-12deg);
        pointer-events: none;
    }

    .topbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        position: relative;
        z-index: 1;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 240px;
    }

    .seal {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #10b981, #6366f1);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        letter-spacing: 0.06em;
        box-shadow: 0 10px 20px rgba(2, 6, 23, 0.10);
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-sizing: border-box;
        overflow: hidden;
    }

    .brand-text .brand-name {
        font-size: 16px;
        font-weight: 900;
        color: #0f172a;
        line-height: 1.1;
        max-width: 250px;
    }

    .brand-text .brand-sub {
        margin-top: 2px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: #64748b;
    }

    .meta {
        flex: 1;
        max-width: 320px;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 10px 12px;
        background: rgba(248, 250, 252, 0.8);
        box-sizing: border-box;
    }

    .meta-row {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 10px;
        padding: 4px 0;
    }

    .meta-label {
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        color: #64748b;
        white-space: nowrap;
    }

    .meta-value {
        font-size: 11px;
        font-weight: 800;
        color: #0f172a;
        text-align: right;
    }

    .body {
        margin-top: 18px;
        position: relative;
        z-index: 1;
    }

    .title-wrap {
        text-align: center;
        margin-top: 6mm;
    }

    .title {
        margin: 0;
        font-size: 26px;
        font-weight: 900;
        letter-spacing: -0.02em;
        color: #0f172a;
    }

    .subtitle {
        margin: 8px auto 0;
        max-width: 520px;
        font-size: 12px;
        color: #475569;
        line-height: 1.5;
    }

    .recipient {
        margin-top: 10mm;
        padding: 14px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.8);
        box-sizing: border-box;
    }

    .field-label {
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        color: #64748b;
    }

    .field-value {
        margin-top: 8px;
        font-size: 22px;
        font-weight: 900;
        color: #0f172a;
        letter-spacing: -0.01em;
        word-break: break-word;
    }

    .grid {
        margin-top: 12px;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
    }

    .pill {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 10px 12px;
        background: #f8fafc;
        box-sizing: border-box;
    }

    .pill-label {
        font-size: 9px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.18em;
        color: #64748b;
    }

    .pill-value {
        margin-top: 4px;
        font-size: 12px;
        font-weight: 900;
        color: #0f172a;
        letter-spacing: 0.04em;
    }

    .statement {
        margin-top: 10mm;
        font-size: 13px;
        color: #0f172a;
        line-height: 1.8;
    }

    .statement p {
        margin: 0 0 10px 0;
    }

    .statement .small {
        margin-top: 8px;
        font-size: 11px;
        color: #475569;
        line-height: 1.7;
    }

    .signatures {
        margin-top: 16mm;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        align-items: end;
    }

    .sig {
        text-align: center;
    }

    .sig-line {
        height: 1px;
        width: 100%;
        background: #0f172a;
        margin-bottom: 8px;
    }

    .sig-name {
        font-size: 12px;
        font-weight: 900;
        color: #0f172a;
    }

    .sig-role {
        margin-top: 2px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #64748b;
    }

    .footer {
        margin-top: 12mm;
        padding-top: 10px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        position: relative;
        z-index: 1;
    }

    .note {
        font-size: 10px;
        color: #475569;
        line-height: 1.6;
        max-width: 420px;
    }

    .foot-meta {
        font-size: 10px;
        color: #64748b;
        text-align: right;
        white-space: nowrap;
    }

    @media print {
        .paper {
            border: none;
            border-radius: 0;
            padding: 0;
            min-height: auto;
            page-break-after: always;
            box-shadow: none;
        }

        body {
            background: white;
        }
    }
</style>
