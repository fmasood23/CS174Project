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
                <label class="foodItemLabel">Food:</label>
                <input class="foodItem" placeholder="Enter food name"></input>
                
                <label class="foodCalsLabel">Calories:</label>
                <input class="foodCals" placeholder="Enter food cals"></input>

                <button class="removeItem" type="button">Remove item</button>
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
            foodItemLabel.style.marginRight = "5px";
            newForm.appendChild(foodItemLabel);

            const foodItemInput = document.createElement("input");
            foodItemInput.className = "foodItem";
            foodItemInput.placeholder = "Enter food name";
            foodItemInput.style.marginRight = "5px";
            newForm.appendChild(foodItemInput);

            const foodCalsLabel = document.createElement("label");
            foodCalsLabel.className = "foodCalsLabel";
            foodCalsLabel.textContent = "Calories:";
            foodCalsLabel.style.marginRight = "5px";
            newForm.appendChild(foodCalsLabel);

            const foodCalsInput = document.createElement("input");
            foodCalsInput.className = "foodCals";
            foodCalsInput.placeholder = "Enter food cals";
            foodCalsInput.style.marginRight = "5px";
            newForm.appendChild(foodCalsInput);

            const removeItemBtn = document.createElement("button");
            removeItemBtn.className = "removeItem";
            removeItemBtn.type = "button";
            removeItemBtn.textContent = "Remove item";
            newForm.appendChild(removeItemBtn);

            mealItemContainer.appendChild(newForm);
            formIndex++;
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