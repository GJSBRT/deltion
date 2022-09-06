<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pizza di Mama</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css">
    <link rel="icon" type="image/x-icon" href="https://gijs.eu/favicon.ico">
</head>

<body>
    <main class="container">
        <article>
            <!-- Een eventuele "Message of the day" -->
            <mark id="motd" style="display: none; padding: 0.75rem; margin-bottom: 0.5rem; border-radius: 0.25rem"></mark>

            <hgroup>
                <h1>Pizza bestellen!</h1>
                <p>Bestel hier gemakkelijk jouw pizza online.</p>
            </hgroup>

            <!-- Laat de bestelling aan de klant zien -->
            <h3>Jouw bestelling</h3>
            <ul id="orderSummary">
                <li>Nog geen pizza's gekozen. 😢</li>
            </ul>

            <!-- Verzamel de bestelling -->
            <div style="display: flex;">
                <select id="pizzaOrderList" class="width: 100%;"></select>
                <button onclick="addPizzaToOrder()" style="width: 3rem; margin-left: 1rem;">+</button>
            </div>

            <hr style="margin-bottom: 1rem;" />

            <!-- Verzamel alle persoons gegevens -->
            <form method="post" action="bestellen.php">
                <input type="text" name="name" placeholder="Naam" aria-label="Naam" autocomplete="firstname" required>
                <input type="number" name="phonenumber" placeholder="Telefoon Nummer" aria-label="Telefoon Nummer" autocomplete="housenumber" required>
                <input type="text" name="street" placeholder="Straat" aria-label="Straat" autocomplete="street" required>
                <input type="number" name="housenumber" placeholder="Huisnummer" aria-label="Huisnummer" autocomplete="housenumber" required>
                <input type="text" name="zipcode" placeholder="Postcode" aria-label="Postcode" autocomplete="zipcode" required>
                <input type="text" name="city" placeholder="Plaats" aria-label="Plaats" autocomplete="city" required>
                <input type="time" name="deliveryTime" placeholder="Bezorgtijd" aria-label="Bezorgtijd" autocomplete="time" required>
                <input type="text" name="orderData" id="orderData" style="display:none;"/>
                <fieldset>
                    <label for="delivery">
                        <input onclick="updateOrderPricing()" type="checkbox" id="delivery" name="delivery">
                        🚚 Pizza laten bezorgen. (€ 5,00)
                    </label>
                </fieldset>

                <!-- Laat de schade zien aan de klant -->
                <h3>Overzicht</h3>
                <ul>
                    <li>Subtotaal: <span id="subtotal">€ 0,00</span></li>
                    <li>Bezorgkosten: <span id="deliveryCost">€ 0,00</span></li>
                    <li>Belasting: <span id="tax">€ 0,00</span></li>
                    <li id="discountListItem" style="display:none;">Korting: <span id="discount"></span></li>
                    <li>Totaal: <span id="total">€ 0,00</span></li>
                </ul>

                <hr style="margin-bottom: 2rem;" />

                <!-- Bestellen maar! -->
                <button disabled id="submitOrder" type="submit" class="primary">Bestel! 👩‍🍳</button>
            </form>
        </article>
    </main>
    <script>
        // Server-side de dag bepalen zodat mensen die hun tijd kunnen verzetten op hun client.
        const day = "<?php echo date('l'); ?>";
        //const day = "Friday"; // Mocht je de verschillende kortings dagen wilt testen dan kan je deze uncommenten en aanpassen.

        // Pizza lijst! 🍕
        const pizzaList = [{
            "name": "Margherita",
            "price": 12.50
        }, {
            "name": "Funghi",
            "price": 12.50
        }, {
            "name": "Marina",
            "price": 13.95
        }, {
            "name": "Hawai",
            "price": 11.50
        }, {
            "name": "Quadro Formaggi",
            "price": 14.50
        }]
        
        // Alle DOM elements die we nodig hebben.
        var motd = document.getElementById("motd");
        var pizzaOrderList = document.getElementById("pizzaOrderList");
        var orderSummary = document.getElementById("orderSummary");
        var submitOrder = document.getElementById("submitOrder");
        var orderData = document.getElementById("orderData");
        var formatter = new Intl.NumberFormat('nl-NL', {
            style: 'currency',
            currency: 'EUR'
        });
        var order = [];

        // Laat een motd zien als er een is.
        if (day == "Monday") {
            motd.style.display = "block"
            motd.innerText = "Vandaag is het actie dag! Alle pizza's zijn € 7,50";
        } else if (day == "Friday") {
            motd.style.display = "block"
            motd.innerText = "Vandaag is er de weekend actie! Alle bestellingen boven de € 20,00 hebben 20% korting!";
        }

        // Laat de pizza lijst zien.
        pizzaList.forEach((pizza) => {
            if (day == "Monday") {
                pizzaOrderList.innerHTML += `<option value="${pizza.name}">${pizza.name} (€ 7,50)</option>`;
            } else {
                pizzaOrderList.innerHTML += `<option value="${pizza.name}">${pizza.name} (${formatter.format(pizza.price)})</option>`;
            }
        })

        // Voeg een pizza toe aan de bestelling.
        function addPizzaToOrder() {
            var pizza = pizzaList.find(pizza => pizza.name == pizzaOrderList.value);
            order.push(pizza);
            updateOrderSummary();
        }

        // Haal een pizza weg van de bestelling.
        function removePizzaFromOrder(pizza) {
            order.splice(order.indexOf(pizzaList.find(pizzaObject => { return pizzaObject.name == pizza; })), 1);
            updateOrderSummary();
        }

        // Update de bestelling.
        function updateOrderSummary() {
            var countOfPizzas = {}

            orderSummary.innerHTML = "";
            order.forEach((pizza) => {
                countOfPizzas[pizza.name] = (countOfPizzas[pizza.name] || 0) + 1;
            })

            for (var pizza in countOfPizzas) {
                if (day == "Monday") {
                    orderSummary.innerHTML += `<li class="orderItem" onclick="removePizzaFromOrder('${pizza}')">${countOfPizzas[pizza]}x ${pizza} <del>(${
                    formatter.format(
                        pizzaList.find(pizzaObject => { 
                            return pizzaObject.name == pizza 
                        }).price * countOfPizzas[pizza]
                        )       
                    })</del> <ins>(${
                    formatter.format(
                        7.5 * countOfPizzas[pizza]
                        )
                    })</ins></li>`;
                } else {
                    orderSummary.innerHTML += `<li class="orderItem" onclick="removePizzaFromOrder('${pizza}')">${countOfPizzas[pizza]}x ${pizza} (${
                    formatter.format(
                        pizzaList.find(pizzaObject => { 
                            return pizzaObject.name == pizza 
                        }).price * countOfPizzas[pizza]
                        )
                    })</li>`;
                }
            }

            /* Zorgt ervoor dat de klant geen lege bestelling kan versturen */
            if (order.length > 0) {
                submitOrder.disabled = false;
            } else {
                submitOrder.disabled = true;
            }

            orderData.value = JSON.stringify(countOfPizzas);
            updateOrderPricing();
        }

        // Update de prijs.
        function updateOrderPricing() {
            var deliveryCost = 0;

            /* Bereken de bezorg kosten mits de klant het wilt laten bezorgen. */
            if (document.getElementById("delivery").checked) {
                document.getElementById("deliveryCost").innerText = formatter.format(5);
                deliveryCost = 5;
            } else {
                document.getElementById("deliveryCost").innerText = formatter.format(0);
                deliveryCost = 0;
            }

            /* Bereken het subtotaal */
            document.getElementById("subtotal").innerText = formatter.format(order.reduce((acc, pizza) => {
                return acc + pizza.price;
            }, 0));

            /* Bereken belasting */
            document.getElementById("tax").innerText = formatter.format(order.reduce((acc, pizza) => {
                return acc + (pizza.price * 0.21);
            }, 0));

            /* Bereken de totale prijs incl belasting en eventuele bezorg kosten */
            document.getElementById("total").innerText = formatter.format(order.reduce((acc, pizza) => {
                if (acc + (pizza.price * 1.21) + deliveryCost > 20 && day == "Friday") {
                    document.getElementById('discountListItem').style.display = 'list-item';
                    document.getElementById('discount').innerText = "20%"
                    return (acc + (pizza.price * 1.21) + deliveryCost) * 0.85;
                } else {
                    document.getElementById('discountListItem').style.display = 'none';
                    document.getElementById('discount').innerText = ""
                    return acc + (pizza.price * 1.21) + deliveryCost;
                }
            }, 0));
        }
    </script>

    <style>
        /* Nog wat kleine stijling */
        .orderItem:hover {
            cursor: pointer;
            text-decoration: line-through;
        }

        h3 {
            margin-bottom: 1rem;
        }
    </style>
</body>

</html>