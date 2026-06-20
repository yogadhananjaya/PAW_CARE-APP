<script>
    function toggleDropdown() {
        var dropdown = document.getElementById("hewanDropdown");
        var caret = document.getElementById("caret-icon");
        dropdown.classList.toggle("show");
        if(dropdown.classList.contains("show")) {
            caret.innerHTML = "▲";
        } else {
            caret.innerHTML = "▼";
        }
    }

    function toggleTransaksi() {
        var dropdown = document.getElementById("transDropdown");
        var icon = document.getElementById("trans-icon");
        dropdown.classList.toggle("show");
        if(dropdown.classList.contains("show")) {
            icon.innerHTML = "▲";
        } else {
            icon.innerHTML = "▼";
        }
    }
</script>
</body>
</html>