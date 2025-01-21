About
Integrating Shopify Order API with WooCommerce, allowing seamless synchronization of products, inventory, and orders between the two platforms.
No APP, using offcial API's

Please Watch video for demo of this integration

https://www.youtube.com/watch?v=ExvuTmnUqPw

Please contact us here

https://www.boldertechnologies.net/cneq

# Get RedMart inventory and update Shopify inventory accordingly.

# Logic between redmart and shopify 

- Assume shopify is 7, redmart Lot is {10, 7, 3} :  10 (total number at redmart) = 7 (available for sale)+3 (schedule for pickup)
Redmart Pre_Lot = {10, 7, 3}
Now there is a new order at Redmart and Redmart Lot is {10, 6, 4}. 
Shopify sync at the next 5min instant. 
Shopify read Redmart Lot and gets Cur_Lot={10, 6, 4}.
The total number at redmart is still 10, no pickup has happened. 
Number of new orders = Cur_Lot->ScheduleForPickup – Pre_Lot->ScheduleForPickup = 1
Shopify stock becomes 7-1=6. It then sets redmart Available-for-Sale to 6. and also updates woo stock to 6.
Read redmart Lot again and save it to Pre_Lot.
Pre_Lot = Cur_Lot； Pre_Lot becomes {10, 6, 4}, 

- Now pickup on the day happens. 3 items have been picked up. No new orders happen.
Redmart Lot becomes {7, 6, 1} 
At the next sync instant, Shopify checks redmart data. Cur_Lot={7, 6, 1}
It compares it with Pre_Lot {10, 6, 4} and found that the total number has changed from 10 to 7. The pickup event is then identified. NoofPickup = Pre_Lot->TotalNumber-Cur->TotalNumber = 10-7= 3.
It checks if there is a new order/order cancellation. 
NoofPickup+Cur_Lot->ScheduledforPickup -Pre_Lot->ScheduledforPickeup = 3+1-4=0.
It confirms there is no new order. Shopify stock wont change and is still 6. Redmart->availableforSale is also equal to Shopify stock and is 6.
Shopify updates woo stock to 6.
Read redmart Lot again and save it to Pre_Lot.
Pre_Lot = Cur_Lot； Pre_Lot becomes {7, 6, 1}, 
 
- Now there is Shopify Online/POS orders or Woo Orders. Assume the orders are 2 items.
Shopify stock changes from 6 to 4. 
Shopify checks redmart Stock Lot, Cur_Lot={7, 6, 1}, which is equal to Pre_Lot, No new orders/pickups  happen at Redmart.
Redmart->AvailableforSale is equal to Shopify Stock 4. Redmart stock becomes {5, 4, 1}
Shopify updates woo stock to 4.
Read redmart Lot again to Cur_Lot and save it to Pre_Lot.
Pre_Lot = Cur_Lot； Pre_Lot becomes {5,4,1}, 


- Now there is a manual input to shopify stock, Shopify Stock changes from 4 to 9.
Shopify check redmart stock Cur_Lot ={5,4,1}
Nothing happens at redmart. 
Redmart->AvailableforSale is equal to Shopify Stock 9. Redmart Lot becomes {10, 9, 1}
Shopify also updates woo stock to 9.
Read Redmart Lot again to Cur_Lot and save it to Pre_Lot.
Pre_Lot = Cur_Lot； Pre_Lot becomes {10, 9,1}, 

- Now Redmart has new orders of 3 and pickup of 1.
Redmart Lot becomes {9, 6, 3}
Shopify has an order of 2 and its stock changes from 9 to 7.
Shopify then sync to Redmart. It reads Redmart Lot, Cur_Lot = {9, 6, 3}
NoofPickup = Pre_Lot->TotalNumber – Cur_Lot->TotalNumber = 10-9 = 1.
Check if there is new order/order cancellations
NoofNewOrders = NoofPickup + Cur_Lot->ScheduleforPickup – Pre_Lot->ScheduleforPickup = 1+3-1 = 3.
There are 3 new orders. Shopify stock decreases and Shopify Stock becomes from 7-3 = 4.
Redmart->AvailableforSale is equal to Shopify Stock 4. Redmart Lot becomes {7, 4, 3}
Shopify also updates woo stock to 4.
Read redmart Lot again to Cur_Lot and save it to Pre_Lot.
Pre_Lot = Cur_Lot； Pre_Lot becomes {7,4,3}, 


TBD

