<h6>Gestione carrello</h6>

<div class="d-grid gap-2 mb-3">
    <button type="submit" form="cartItemsForm" name="deleteSelected" value="1" id="btnEliminaSelezionati" class="btn btn-outline-danger btn-sm" disabled>
        Elimina selezionati
    </button>

    <form method="post" action="" onsubmit="return confirm('Vuoi davvero svuotare tutto il carrello?');">
        <button type="submit" name="deleteAllCart" value="1" class="btn btn-danger btn-sm w-100">
            Elimina tutto
        </button>
    </form>
</div>

<script>
    // abilita "Elimina selezionati" solo quando almeno una riga è selezionata
    window.addEventListener('DOMContentLoaded', function () {
        var btn = document.getElementById('btnEliminaSelezionati');
        var form = document.getElementById('cartItemsForm');
        if (!btn || !form) { return; }

        function aggiornaStatoBottone() {
            var selezionati = form.querySelectorAll('input[name="selected[]"]:checked');
            btn.disabled = selezionati.length === 0;
        }

        form.addEventListener('change', function (e) {
            if (e.target && e.target.name === 'selected[]') {
                aggiornaStatoBottone();
            }
        });
    });
</script>
