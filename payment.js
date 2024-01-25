


function stripeTokenHandler(token) {
    // Send token to your server for processing
    fetch('/charge', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ token: token }),
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        // Handle the server response as needed
        if (data.success) {
            alert('Payment successful!');
        } else {
            alert('Payment failed. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
