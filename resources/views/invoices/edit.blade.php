<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Invoice | E-Invoice</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('invoice3.png') }}">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        .fade-in {
            animation: fadeInUp .8s cubic-bezier(.4,0,.2,1) forwards;
        }
        @keyframes fadeInUp {
            from { opacity:0; transform: translateY(30px); }
            to   { opacity:1; transform: translateY(0); }
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(99,102,241,.35);
            border-color: rgb(99 102 241);
        }
    </style>
</head>

<body class="bg-gray-100">
<div class="flex">

@include('layouts.sidebar_admin')

<div class="flex-1 min-h-screen flex flex-col">
@include('layouts.header_admin')

<main class="p-6 max-w-6xl mx-auto fade-in space-y-6">

    <!-- TITLE -->
    <h1 class="text-2xl font-semibold flex items-center gap-2">
        <i data-lucide="file-edit" class="text-indigo-600"></i>
        Edit Invoice
    </h1>

    <!-- FORM -->
    <form method="POST"
          action="/invoices/update/{{ $invoice->id }}"
          class="space-y-6 bg-white p-6 rounded-2xl shadow">
        @csrf

        <!-- ================= HEADER ================= -->
        <div class="grid md:grid-cols-2 gap-5">
            <div>
                <label class="text-sm">Tanggal Invoice</label>
                <input type="date"
                       name="invoice_date"
                       value="{{ $invoice->invoice_date }}"
                       required
                       class="w-full px-3 py-2 border rounded-xl input-focus">
            </div>

            <div>
                <label class="text-sm">Due Date</label>
                <input type="date"
                       name="due_date"
                       value="{{ $invoice->due_date }}"
                       required
                       class="w-full px-3 py-2 border rounded-xl input-focus">
            </div>

            <div class="md:col-span-2">
                <label class="text-sm">Catatan</label>
                <input type="text"
                       name="notes"
                       value="{{ $invoice->notes }}"
                       class="w-full px-3 py-2 border rounded-xl input-focus">
            </div>
        </div>

        <!-- ================= ITEMS ================= -->
        <div>
            <h2 class="font-semibold mb-2 flex items-center gap-2">
                <i data-lucide="list"></i>
                Item Invoice
            </h2>

            <table class="w-full text-sm border rounded-xl overflow-hidden">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Deskripsi</th>
                    <th class="p-2 w-20">Qty</th>
                    <th class="p-2 w-24">Unit</th>
                    <th class="p-2 w-32">Harga</th>
                    <th class="p-2 w-32">Total</th>
                    <th class="p-2 w-10"></th>
                </tr>
                </thead>
                <tbody id="items">
                @foreach($items as $i => $item)
                <tr class="border-t">
                    <td class="p-1">
                        <input name="items[{{ $i }}][description]"
                               value="{{ $item->item_description }}"
                               required
                               class="border p-1 w-full input-focus">
                    </td>
                    <td class="p-1">
                        <input name="items[{{ $i }}][qty]"
                               type="number"
                               value="{{ $item->quantity }}"
                               min="1"
                               required
                               class="border p-1 w-full qty input-focus">
                    </td>
                    <td class="p-1">
                        <input name="items[{{ $i }}][unit]"
                               value="{{ $item->unit }}"
                               class="border p-1 w-full input-focus">
                    </td>
                    <td class="p-1">
                        <input name="items[{{ $i }}][price]"
                               type="number"
                               value="{{ $item->price }}"
                               min="0"
                               required
                               class="border p-1 w-full price input-focus">
                    </td>
                    <td class="p-1">
                        <input name="items[{{ $i }}][total]"
                               value="{{ $item->total_price }}"
                               readonly
                               class="border p-1 w-full total bg-gray-100">
                    </td>
                    <td class="p-1 text-center">
                        <button type="button"
                                onclick="this.closest('tr').remove(); calc();"
                                class="text-red-600">✖</button>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>

            <button type="button"
                    onclick="addItem()"
                    class="mt-3 flex items-center gap-2 text-indigo-600">
                <i data-lucide="plus-circle"></i>
                Tambah Item
            </button>
        </div>

        <!-- ================= TOTAL ================= -->
        <div class="grid md:grid-cols-3 gap-4 text-right">
            <div>
                <label>Subtotal</label>
                <input name="subtotal"
                       value="{{ $invoice->subtotal }}"
                       readonly
                       class="w-full px-3 py-2 border rounded-xl">
            </div>
            <div>
                <label>PPN 11%</label>
                <input name="tax_ppn"
                       value="{{ $invoice->tax_ppn }}"
                       readonly
                       class="w-full px-3 py-2 border rounded-xl">
            </div>
            <div>
                <label>Grand Total</label>
                <input name="grand_total"
                       value="{{ $invoice->grand_total }}"
                       readonly
                       class="w-full px-3 py-2 border rounded-xl font-semibold">
            </div>
        </div>

        <!-- ================= ACTION ================= -->
        <div class="flex justify-end gap-3">
            <a href="/invoices"
               class="px-5 py-2 border rounded-xl">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2 bg-emerald-600 text-white rounded-xl
                           flex items-center gap-2">
                <i data-lucide="save"></i>
                Update Invoice
            </button>
        </div>

    </form>

</main>

@include('layouts.footer_admin')
</div>
</div>

<script>
    lucide.createIcons();

    const items = document.getElementById('items');
    let index = {{ count($items) }};

    function addItem() {
        items.insertAdjacentHTML('beforeend', `
        <tr class="border-t">
            <td class="p-1">
                <input name="items[${index}][description]"
                       required
                       class="border p-1 w-full input-focus">
            </td>
            <td class="p-1">
                <input name="items[${index}][qty]" type="number" value="1"
                       min="1"
                       required
                       class="border p-1 w-full qty input-focus">
            </td>
            <td class="p-1">
                <input name="items[${index}][unit]"
                       class="border p-1 w-full input-focus">
            </td>
            <td class="p-1">
                <input name="items[${index}][price]" type="number"
                       min="0"
                       required
                       class="border p-1 w-full price input-focus">
            </td>
            <td class="p-1">
                <input name="items[${index}][total]" readonly
                       class="border p-1 w-full total bg-gray-100">
            </td>
            <td class="p-1 text-center">
                <button type="button"
                        onclick="this.closest('tr').remove(); calc();"
                        class="text-red-600">✖</button>
            </td>
        </tr>
        `);
        index++;
    }

    items.addEventListener('input', calc);

    function calc() {
        let subtotal = 0;

        document.querySelectorAll('#items tr').forEach(tr => {
            const qty   = parseFloat(tr.querySelector('.qty')?.value || 0);
            const price = parseFloat(tr.querySelector('.price')?.value || 0);
            const total = qty * price;

            tr.querySelector('.total').value = total;
            subtotal += total;
        });

        const tax = subtotal * 0.11;

        document.querySelector('[name=subtotal]').value = subtotal.toFixed(2);
        document.querySelector('[name=tax_ppn]').value = tax.toFixed(2);
        document.querySelector('[name=grand_total]').value = (subtotal + tax).toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', calc);
</script>

</body>
</html>
