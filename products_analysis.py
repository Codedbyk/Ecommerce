import pandas as pd
import matplotlib.pyplot as plt

# Products CSV
data = pd.read_csv("products.csv")

print(data.head())

print(data.isnull().sum())

# Category Distribution
category = data["category"].value_counts()

category.plot(
    kind="pie",
    autopct='%1.1f%%'
)

plt.title("Category Distribution")

plt.ylabel("")

plt.show()