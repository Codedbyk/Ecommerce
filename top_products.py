import pandas as pd
import matplotlib.pyplot as plt

# Read CSV
data = pd.read_csv("order_items.csv")


# Show rows
print(data.head())

# Most sold products
top_products = data.groupby("product_id")["quantity"].sum()

# Print result
print(top_products)

# Bar chart
top_products.plot(kind="bar")

plt.title("Most Sold Products")

plt.xlabel("Product ID")

plt.ylabel("Quantity Sold")

plt.show()