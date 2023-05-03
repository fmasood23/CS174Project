<!DOCTYPE html>
<html>

<head>
    <title>Calorie Counting Form</title>
</head>

<body>
    <h2>Calorie Counting Form</h2>
    <form method="post" action="CalorieCountingForm.php">
        <label for="meal_name">Meal Name:</label>
        <select name="meal_name" id="meal_name" required>
            <?php
            // Open the "food_calories.csv" file for reading
            if (($handle = fopen("food_calories.csv", "r")) !== false) {
                // Read the first line as the column headers
                $header = fgetcsv($handle, 1000, ",");
                // Loop through the remaining lines and create a dropdown option for each food item
                while (($data = fgetcsv($handle, 1000, ",")) !== false) {
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
                // Reset the Calorie Amount back to 0 after submitting the form
                if (isset($_POST["meal_name"]) && isset($_POST["serving_size"])) {
                    $_POST["calorie_amount"] = 0;
                }
            } ?>
        </select><br><br>
        <label for="serving_size">Serving Size:</label>
        <input type="number" name="serving_size" id="serving_size" value="0" min="0" step="any" required><br><br>

        <label for="calorie_amount">Calorie Amount:</label>
        <input type="number" name="calorie_amount" id="calorie_amount" value="0" readonly required <?php if (
            isset($_POST["calorie_amount"])
        ) {
            echo "value=\"" . $_POST["calorie_amount"] . "\"";
        } ?>><br><br>

        <input type="submit" value="Submit">

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

    <?php if (isset($_POST["meal_name"]) && isset($_POST["serving_size"])) {
        $meal_name = $_POST["meal_name"];
        $serving_size = $_POST["serving_size"];

        // Open the "food_calories.csv" file for reading
        if (($handle = fopen("food_calories.csv", "r")) !== false) {
            // Read the first line as the column headers
            $header = fgetcsv($handle, 1000, ",");
            // Loop through the remaining lines to find the selected meal
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if ($data[0] == $meal_name) {
                    $calorie_amount = round(
                        ($data[2] * $serving_size) / $data[1]
                    ); // Calculate the calorie amount based on serving size
                    echo "<h2>Meal Details</h2>";
                    echo "<p>Meal Name: " . $meal_name . "</p>";
                    echo "<p>Serving Size: " .
                        $serving_size .
                        " " .
                        $data[3] .
                        "</p>";
                    echo "<p>Calorie Amount: " . $calorie_amount . "</p>";
                    break;
                }
            }
            fclose($handle);
        }
    } ?>
</body>

</html>