<!DOCTYPE html>
<html>

<head>
    <title>Calorie Counting Form</title>
</head>

<body>
    <h2>Calorie Counting Form</h2>
    <form method="post" action="CalorieCountingForm.php">
        <label for="meal_name">Food:</label>
        <select name="meal_name" id="meal_name" required>
            <?php
            if (($handle = fopen("food_calories.csv", "r")) !== false) {
                $header = fgetcsv($handle, 564, ",");
                while (($data = fgetcsv($handle, 564, ",")) !== false) {
                    $selected = isset($_POST["meal_name"]) && $_POST["meal_name"] == $data[0] ? "selected" : "";
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
                if (isset($_POST["meal_name"]) && isset($_POST["serving_size"])) {
                    $_POST["calorie_amount"] = 0;
                }
            } ?>
        </select><br><br>
        <label for="serving_size">Serving Size:</label>
        <input type="number" name="serving_size" id="serving_size" value="0" min="0" step="any" required><br><br>

        <label for="calorie_amount">Calories:</label>
        <input type="number" name="calorie_amount" id="calorie_amount" min="0" step="any" required <?php if (
            isset($_POST["calorie_amount"])
        ) {
            echo "value=\"" . $_POST["calorie_amount"] . "\"";
        } else {
            echo "value=\"0\"";
        } ?> oninput="calorieAmountInputChanged()"><br><br>

        <script>
            function calorieAmountInputChanged() {
                const servingSizeInput = document.getElementById('serving_size');
                const calorieAmountInput = document.getElementById('calorie_amount');
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
            const servingSizeInput = document.getElementById('serving_size');
            const calorieAmountInput = document.getElementById('calorie_amount');
            const mealNameSelect = document.getElementById('meal_name');

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
</body>

</html>