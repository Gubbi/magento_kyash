# magento_kyash
Magento Integration Kit for the [Kyash Payment Gateway](http://www.kyash.com/).<br>
Download the latest package [here](https://github.com/Gubbi/magento_kyash/releases/download/v1.21/magento_kyash-1.21.zip).

## Installation
1. Login to your `Magento Connect Manager` directly or from Admin dashboard go to<br>
`System`->`Magento Connect`->`Magento Connect Manager`
2. In section `Direct package file upload`, choose package file [magento_kyash.zip](https://github.com/Gubbi/magento_kyash/releases/download/v1.21/magento_kyash-1.21.zip) and upload.


## Configuration
1. After successful upload, from your Admin dashboard go to <br> `System`->`Configuration`->`Sales`->`Payment Methods` <br> You will find `Kyash` listed as one of the payment method.
2. Enable Kyash plugin and fill your Kyash Account credentials (available in your Kyash Account Settings page).
3. There are two types of credentials you can enter: 
  - To test the system, use the *Developer* credentials.
  - To make the system live and accept your customer payments use the *Production* credentials.
4. Click `Save Config` to save the changes.
5. Copy the *Callback URL* (e.g. `http://www.yourstore.com/?action=kyash-handler`) to your Kyash Account Settings and click `Set` to update the callback URL.

## Testing the Integration.
1. Place an order from your Magento store.
2. Pick *Kyash - Pay at a nearby shop* as the payment option.
3. Note down the *KyashCode* generated for this order.
4. In a live system, the customer will take this KyashCode to a nearby shop and make the payment using cash.
5. But since we are testing, Login to your Kyash Account.
6. Enter the KyashCode in the search box.
7. You should see a `Mark as Paid` button there.
8. Clicking this should change the order status from *Pending* to *Processing* in your *Magento* order details page.

## Support
Contact developers@kyash.com for any issues you might be facing with this Kyash extension or call +91 8050114225.

