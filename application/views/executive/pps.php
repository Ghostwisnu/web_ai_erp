<head>
    <meta charset="utf-8">
    <title>Production Progress Summary</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --text: #111;
            --text-strong: #000;
            --border: #ddd;
            --bg-th: #f2f2f2;
            --bg-zebra: #fafafa;
            --highlight: #fffae0;
        }

        html,
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            color: var(--text);
            margin: 0
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 16px
        }

        h2 {
            margin: 0 0 14px;
            color: var(--text-strong);
            font-weight: 700
        }

        .toolbar {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-bottom: 12px
        }

        .search {
            flex: 1;
            display: flex;
            align-items: center;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 10px;
            background: #fff;
        }

        .search input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 14px;
            color: var(--text);
            background: transparent;
        }

        .muted {
            font-size: 12px;
            color: #555
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto
        }

        th,
        td {
            border: 1px solid var(--border);
            padding: 8px;
            text-align: left
        }

        th {
            background: var(--bg-th);
            position: sticky;
            top: 0;
            z-index: 1;
            color: var(--text-strong);
            font-weight: 700
        }

        tr:nth-child(even) {
            background: var(--bg-zebra)
        }

        .num {
            text-align: right;
            white-space: nowrap;
            font-variant-numeric: tabular-nums
        }

        .center {
            text-align: center
        }

        .nowrap {
            white-space: nowrap
        }

        .hide {
            display: none
        }

        mark {
            background: var(--highlight);
            padding: 0 2px
        }

        .table-wrap {
            max-height: 70vh;
            overflow: auto;
            border: 1px solid var(--border);
            border-radius: 8px
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Production Progress Summary</h2>

        <div class="toolbar">
            <div class="search" title="Ketik untuk mencari WO Number, Art & Color, atau Brand">
                🔎&nbsp;<input id="q" type="text" placeholder="Cari WO Number / Art & Color / Brand…" autocomplete="off">
            </div>
            <div class="muted" id="countInfo"></div>
        </div>

        <div class="table-wrap">
            <table id="ppsTable">
                <thead>
                    <tr>
                        <th class="center" style="width:56px">No.</th>
                        <th>WO Number</th>
                        <th>Art &amp; Color</th>
                        <th>Brand</th>
                        <th class="num">Cutting</th>
                        <th class="num">Sewing</th>
                        <th class="num">Semi Warehouse</th>
                        <th class="num">Lasting</th>
                        <th class="num">Finishing</th>
                        <!-- <th class="num">Packaging</th> -->
                        <th class="num">Finish Goods</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rows)): ?>
                        <?php $no = 1;
                        foreach ($rows as $row): ?>
                            <tr>
                                <td class="center"><?= $no++; ?></td>
                                <td class="nowrap col-wo" data-text="<?= htmlspecialchars($row['wo_number']); ?>"><?= htmlspecialchars($row['wo_number']); ?></td>
                                <td class="col-art" data-text="<?= htmlspecialchars($row['artcolor_name']); ?>"><?= htmlspecialchars($row['artcolor_name']); ?></td>
                                <td class="col-brand" data-text="<?= htmlspecialchars($row['brand_name']); ?>"><?= htmlspecialchars($row['brand_name']); ?></td>
                                <td class="num"><?= number_format($row['cutting']); ?></td>
                                <td class="num"><?= number_format($row['sewing']); ?></td>
                                <td class="num"><?= number_format($row['semi_warehouse']); ?></td>
                                <td class="num"><?= number_format($row['lasting']); ?></td>
                                <td class="num"><?= number_format($row['finishing']); ?></td>
                                <!-- <td class="num"><?= number_format($row['packaging']); ?></td> -->
                                <td class="num"><strong><?= number_format($row['finish_goods']); ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="11" class="center">Tidak ada data.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // simple debounce
        function debounce(fn, delay = 150) {
            let t;
            return (...args) => {
                clearTimeout(t);
                t = setTimeout(() => fn(...args), delay);
            }
        }

        const q = document.getElementById('q');
        const table = document.getElementById('ppsTable');
        const tbody = table.querySelector('tbody');
        const countInfo = document.getElementById('countInfo');

        // Simpel highlighter: ganti isi sel WO/Art/Brand dengan mark
        function highlight(cell, term) {
            const text = cell.getAttribute('data-text') || cell.textContent;
            if (!term) {
                cell.innerHTML = text;
                return;
            }
            const esc = term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const re = new RegExp(`(${esc})`, 'ig');
            cell.innerHTML = text.replace(re, '<mark>$1</mark>');
        }

        function clearHighlights(row) {
            ['col-wo', 'col-art', 'col-brand'].forEach(cls => {
                const cell = row.querySelector('.' + cls);
                if (cell) {
                    highlight(cell, '');
                }
            });
        }

        function applySearch() {
            const term = (q.value || '').trim().toLowerCase();
            let shown = 0,
                total = 0;
            Array.from(tbody.rows).forEach((tr, idx) => {
                if (tr.cells.length === 0) return;
                total++;
                const wo = (tr.querySelector('.col-wo')?.getAttribute('data-text') || tr.cells[1].textContent || '').toLowerCase();
                const art = (tr.querySelector('.col-art')?.getAttribute('data-text') || tr.cells[2].textContent || '').toLowerCase();
                const brand = (tr.querySelector('.col-brand')?.getAttribute('data-text') || tr.cells[3].textContent || '').toLowerCase();

                const hit = !term || wo.includes(term) || art.includes(term) || brand.includes(term);

                if (hit) {
                    tr.classList.remove('hide');
                    // No. urut dinamis setelah filter
                    tr.cells[0].textContent = (++shown).toString();
                    // highlight
                    highlight(tr.querySelector('.col-wo'), term);
                    highlight(tr.querySelector('.col-art'), term);
                    highlight(tr.querySelector('.col-brand'), term);
                } else {
                    tr.classList.add('hide');
                    clearHighlights(tr);
                }
            });
            countInfo.textContent = shown ? `${shown} dari ${total} baris` : 'Tidak ada hasil';
        }

        q.addEventListener('input', debounce(applySearch, 120));
        window.addEventListener('DOMContentLoaded', applySearch);
    </script>
</body>
</div>