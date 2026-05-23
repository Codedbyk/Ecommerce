import pandas as pd
import matplotlib.pyplot as plt

# Orders CSV
data = pd.read_csv("orders.csv")

# Show first rows
print(data.head())

# Check missing values
print(data.isnull().sum())


# ================= ORDER STATUS BAR CHART =================

status = data["status"].value_counts()

status.plot(kind="bar")

plt.title("Order Status Analysis")

plt.xlabel("Status")

plt.ylabel("Count")

plt.show()


# ================= ORDER STATUS PIE CHART =================

status.plot(
    kind="pie",
    autopct='%1.1f%%'
)

plt.title("Order Status Pie Chart")

plt.ylabel("")

plt.show()


# ================= MONTHLY SALES LINE CHART =================

data["created_at"] = pd.to_datetime(data["created_at"])

sales = data.groupby(
    data["created_at"].dt.month
)["total"].sum()

sales.plot(kind="line")

plt.title("Monthly Sales")

plt.xlabel("Month")

plt.ylabel("Sales")

plt.show()