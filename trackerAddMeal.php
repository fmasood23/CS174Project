<!DOCTYPE html>
<html>

<head>
    <title></title>

</head>

<body>
    <div style="text-align: center">
        <form id="dateSelect" method="post">
            <label name="mealType">Meal:</label>
            <select name="mealType">
                <option value="breakfast">Breakfast</option>
                <option value="lunch">Lunch</option>
                <option value="dinner">Dinner</option>
                <option value="other">Other</option>
            </select>
        </form>

        <div id="mealItemContainer">
            <form class="mealItemForm">
                <label for="foodItemLabel">Food:</label>
                <select name="foodItemSelect" id="foodItemSelect" required>
                    <?php
                    if (($handle = fopen("food_calories.csv", "r")) !== false) {
                        $header = fgetcsv($handle, 564, ",");
                        while (($data = fgetcsv($handle, 564, ",")) !== false) {
                            $selected = isset($_POST["foodItemSelect"]) && $_POST["foodItemSelect"] == $data[0] ? "selected" : "";
                            echo "<option value=\"" .
                                $data[0] .
                                "\" data-calories=\"" .
                                $data[2] .
                                "\" data-serving-unit=\"" .
                                $data[3] .
                                "\"" . $selected . ">" .
                                $data[0] .
                                "</option>";
                        }
                        fclose($handle);
                        if (isset($_POST["foodItemSelect"]) && isset($_POST["servingSize"])) {
                            $_POST["foodCals"] = 0;
                        }
                    } ?>
                </select>
                <label for="servingSizeLabel">Serving Size:</label>
                <input type="number" name="servingSize" id="servingSize" value="0" min="0" step="any" required>
                <label for="foodCalsLabel">Calories:</label>
                <input type="number" name="foodCals" id="foodCals" min="0" step="any" required <?php if (
                    isset($_POST["foodCals"])
                ) {
                    echo "value=\"" . $_POST["foodCals"] . "\"";
                } else {
                    echo "value=\"0\"";
                } ?> oninput="calorieAmountInputChanged()">
                <button class="removeItem" type="button">Remove item</button>
                <script>
                    function calorieAmountInputChanged() {
                        const servingSizeInput = document.getElementById('servingSize');
                        const calorieAmountInput = document.getElementById('foodCals');
                        const selectedOption = mealNameSelect.options[mealNameSelect.selectedIndex];
                        const caloriePerServing = parseFloat(selectedOption.getAttribute('data-calories'));
                        const servingUnit = selectedOption.getAttribute('data-serving-unit');
                        const servingSize = parseFloat(servingSizeInput.value);
                        const calorieAmount = parseFloat(calorieAmountInput.value);

                        if (!isNaN(calorieAmount) && calorieAmount >= 0) {
                            servingSizeInput.value = (calorieAmount / caloriePerServing).toFixed(2);
                        } else {
                            servingSizeInput.value = 0;
                        }
                    }
                </script>

                <script>
                    const servingSizeInput = document.getElementById('servingSize');
                    const calorieAmountInput = document.getElementById('foodCals');
                    const mealNameSelect = document.getElementById('foodItemSelect');

                    mealNameSelect.addEventListener('change', function () {
                        calorieAmountInput.value = 0;
                    });

                    servingSizeInput.addEventListener('input', function () {
                        const selectedOption = mealNameSelect.options[mealNameSelect.selectedIndex];
                        const caloriePerServing = parseFloat(selectedOption.getAttribute('data-calories'));
                        const servingUnit = selectedOption.getAttribute('data-serving-unit');
                        const servingSize = parseFloat(servingSizeInput.value);
                        const calorieAmount = Math.round(caloriePerServing * servingSize);

                        calorieAmountInput.value = calorieAmount;
                    });
                </script>

            </form>

            <?php if (isset($_POST["foodItemSelect"]) && isset($_POST["servingSize"])) {
                $foodItemSelect = $_POST["foodItemSelect"];
                $servingSize = $_POST["servingSize"];
                if (($handle = fopen("food_calories.csv", "r")) !== false) {
                    $header = fgetcsv($handle, 564, ",");
                    while (($data = fgetcsv($handle, 564, ",")) !== false) {
                        if ($data[0] == $foodItemSelect) {
                            $foodCals =
                                ($data[2] * $servingSize) / $data[1]
                            ;
                        }
                    }
                    fclose($handle);
                }
            } ?>
            </form>
        </div>

        <button id="addFood" type="button">Add food item</button>
    </div>

    <script>
        const mealItemContainer = document.getElementById("mealItemContainer");
        const addFoodBtn = document.getElementById("addFood");
        let formIndex = 0;

        addFoodBtn.addEventListener("click", () => {
            const newForm = document.createElement("form");
            newForm.className = "mealItemForm";

            const foodItemLabel = document.createElement("label");
            foodItemLabel.className = "foodItemLabel";
            foodItemLabel.textContent = "Food:";
            foodItemLabel.style.marginRight = "6px";
            newForm.appendChild(foodItemLabel);

            const foodItemSelect = document.createElement("select");
            foodItemSelect.className = "foodItem";
            foodItemSelect.style.marginRight = "5px";
            newForm.appendChild(foodItemSelect);

            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'food_calories.csv');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const data = xhr.responseText;
                    const rows = data.split("\n").map(row => row.split(","));
                    const header = rows.shift();
                    rows.forEach(row => {
                        const option = document.createElement("option");
                        option.value = row[0];
                        option.dataset.calories = row[2];
                        option.dataset.servingUnit = row[3];
                        option.text = row[0];
                        foodItemSelect.appendChild(option);
                    });
                } else {
                    console.error(xhr.statusText);
                }
            };
            xhr.onerror = function () {
                console.error(xhr.statusText);
            };
            xhr.send();

            const servingSizeLabel = document.createElement("label");
            servingSizeLabel.className = "servingSizeLabel";
            servingSizeLabel.textContent = "Serving Size:";
            servingSizeLabel.style.marginRight = "6px";
            newForm.appendChild(servingSizeLabel);

            const servingSizeInput = document.createElement("input");
            servingSizeInput.type = "number";
            servingSizeInput.name = `servingSize_${formIndex}`;
            servingSizeInput.id = `servingSize_${formIndex}`;
            servingSizeInput.value = "0";
            servingSizeInput.min = "0";
            servingSizeInput.step = "any";
            servingSizeInput.required = true;
            servingSizeInput.style.marginRight = "6px";
            servingSizeInput.addEventListener("input", () => {
                const selectedOption = foodItemSelect.options[foodItemSelect.selectedIndex];
                const caloriePerServing = parseFloat(selectedOption.getAttribute('data-calories'));
                const servingSize = parseFloat(servingSizeInput.value);
                const calorieAmount = Math.round(caloriePerServing * servingSize);
                calorieAmountInput.value = calorieAmount;
            });
            newForm.appendChild(servingSizeInput);

            const foodCalsLabel = document.createElement("label");
            foodCalsLabel.className = "foodCalsLabel";
            foodCalsLabel.textContent = "Calories:";
            foodCalsLabel.style.marginRight = "6px";
            newForm.appendChild(foodCalsLabel);

            const calorieAmountInput = document.createElement("input");
            calorieAmountInput.type = "number";
            calorieAmountInput.name = `foodCals_${formIndex}`;
            calorieAmountInput.id = `foodCals_${formIndex}`;
            calorieAmountInput.value = "0";
            calorieAmountInput.min = "0";
            calorieAmountInput.step = "any";
            calorieAmountInput.required = true;
            calorieAmountInput.style.marginRight = "6px";
            calorieAmountInput.addEventListener("input", () => {
                const selectedOption = foodItemSelect.options[foodItemSelect.selectedIndex];
                const caloriePerServing = parseFloat(selectedOption.getAttribute('data-calories'));
                const servingSize = parseFloat(servingSizeInput.value);
                const calorieAmount = parseFloat(calorieAmountInput.value);

                if (!isNaN(calorieAmount) && calorieAmount >= 0) {
                    servingSizeInput.value = (calorieAmount / caloriePerServing).toFixed(2);
                } else {
                    servingSizeInput.value = 0;
                }
            });
            newForm.appendChild(calorieAmountInput);


            const removeItemBtn = document.createElement("button");
            removeItemBtn.className = "removeItem";
            removeItemBtn.type = "button";
            removeItemBtn.textContent = "Remove item";
            newForm.appendChild(removeItemBtn);


            formIndex++;
            mealItemContainer.appendChild(newForm);
        });

        mealItemContainer.addEventListener("click", (event) => {
            if (event.target.classList.contains("removeItem")) {
                event.target.closest(".mealItemForm").remove();
                formIndex--;
            }
        });
    </script>
</body>

</html>