const paypalButtonsComponent = paypal_sdk.Buttons({
    // optional styling for buttons
    // https://developer.paypal.com/docs/checkout/standard/customize/buttons-style-guide/
    style: {
      color: "gold",
      shape: "rect",
      layout: "vertical"
    },

    // set up the transaction
    createOrder: (data, actions) => {
        // pass in any options from the v2 orders create call:
        // https://developer.paypal.com/api/orders/v2/#orders-create-request-body
        let total_price = parseFloat($('#ad_campaign_total').text()).toFixed(2);
        const createOrderPayload = {
            purchase_units: [
                {
                    "description":"Ad Campaign",
                    "amount":{
                        "currency_code": "USD",
                        "value": total_price
                    }
                }
            ]
        };

        return actions.order.create(createOrderPayload);
    },

    // finalize the transaction
    onApprove: (data, actions) => {
        const captureOrderHandler = (details) => {
            const payerName = details.payer.name.given_name;
            console.log('Transaction completed');
        };

        return actions.order.capture().then(captureOrderHandler);
    },

    // handle unrecoverable errors
    onError: (err) => {
        console.error('An error prevented the buyer from checking out with PayPal');
    }
});

paypalButtonsComponent
    .render("#paypal-button-container")
    .catch((err) => {
        console.error('PayPal Buttons failed to render');
    });