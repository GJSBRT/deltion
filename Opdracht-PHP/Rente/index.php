<?php
$displayTable = 'none';
$tableData = '';

if (isset($_POST['amount']) && isset($_POST['percentage'])) {
    $amount = $_POST['amount'];
    $percentage = $_POST['percentage'];
    $radio = $_POST['radio'];

    if ($radio == "10years") {
        for ($i = 1; $i <= 10; $i++) {
            $tableData .=   '<tr>
                                <td>' . $i . '</td>
                                <td>€ ' . number_format(round($amount * pow((1 + ($percentage / 100)), $i), 2), 2, ',', '.') . '</td>
                            </tr>';
        }
    } else {
        $i = 1;
        while (true) {
            $number = round($amount * pow((1 + ($percentage / 100)), $i), 2);

            $tableData .=   '<tr>
                                <td>' . $i . '</td>
                                <td>€ ' . number_format(round($amount * pow((1 + ($percentage / 100)), $i), 2), 2, ',', '.') . '</td>
                            </tr>';
            
            if ($number >= ($amount * 2)) {
                break;
            }
            $i++;
        }
    }

    $displayTable = 'block';
}

?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rente berekenen</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css">
</head>

<body>
    <main class="container">
        <article>
            <hgroup>
                <h1>Rente berekenen</h1>
                <p>Bereken gemakkelijk rente over 10 jaar.</p>
            </hgroup>
            <form action="" method="POST">
                <input onchange="checkValid()" id="amount" type="number" name="amount" placeholder="Ingelegd Bedrag" aria-label="Ingelegd Bedrag" required>
                <input onchange="checkValid()" id="percentage" type="number" name="percentage" placeholder="Rente Percentage" aria-label="Rente Percentage" required>

                <fieldset>
                    <label for="10years">
                        <input type="radio" id="10years" name="radio" value="10years" checked>
                        Eindbedrag na 10 jaar
                    </label>
                    <label for="dubbled">
                        <input type="radio" id="dubbled" name="radio" value="dubbled">
                        Eindbedrag verdubbeld
                    </label>
                </fieldset>

                <button disabled id="submitButton" type="submit" class="primary">Bereken! 🧮</button>
            </form>

            <section id="tables" style="margin-bottom: 0px;display:<?php echo $displayTable; ?>;">
                <h2 style="margin-bottom: 1rem;">Resultaat</h2>
                <figure>
                    <table role="grid">
                        <thead>
                            <tr>
                                <th scope="col">Jaar</th>
                                <th scope="col">Bedrag</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $tableData; ?>
                        </tbody>
                    </table>
                </figure>
            </section>
        </article>
    </main>
</body>

<script>
    function checkValid() {
        var submitButton = document.getElementById('submitButton');
        var amount = document.getElementById("amount");
        var percentage = document.getElementById("percentage");

        if (amount.value > 1) {
            amount.setAttribute('aria-invalid', 'false');
        } else {
            amount.setAttribute('aria-invalid', 'true');
        }

        if (percentage.value > 1) {
            percentage.setAttribute('aria-invalid', 'false');
        } else {
            percentage.setAttribute('aria-invalid', 'true');
        }

        if (percentage.value > 1 && amount.value > 1) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    }
</script>

</html>