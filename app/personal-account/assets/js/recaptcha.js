const GoogleRecaptcha = require('google-recaptcha');

const googleRecaptcha = new GoogleRecaptcha({secret: 'RECAPTCHA_SECRET_KEY'});

let http;
http.on('POST', (request, response) => {
    const recaptchaResponse = request.body['g-recaptcha-response'];

    googleRecaptcha.verify({response: recaptchaResponse}, (error) => {
        if (error) {
            return response.send({isHuman: false});
        }

        return response.send({isHuman: true});
    })
})