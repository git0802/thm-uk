const phrases = {
    install(Vue) {
        const appVersion = (window.laravel && window.laravel.appVersion) || 'co.uk';
        const phrase = {
            coUk: {
                currencySm: '£',
                weightSm: 'kg',
                countryShortCode: 'GB',

                minWeight: 30,
                maxWeight: 250,

                minHeight: 50,
                maxHeight: 255,

                goalList: [
                    'Lose 1 kg per week',
                    'Lose 0.5 kg per week',
                    'Lose 0.25 kg per week',
                    'Maintain weight',
                    'Gain 0.25 kg per week',
                    'Gain 0.5 kg per week',
                    'Gain 1 kg per week'
                ],
                goalListValues: [
                    -1,
                    -0.5,
                    -0.25,
                    0,
                    0.25,
                    0.5,
                    1
                ]
            },
            com: {
                currencySm: '$',
                weightSm: 'lbs',
                countryShortCode: 'US',

                minWeight: 60,
                maxWeight: 600,

                minHeight: 25,
                maxHeight: 100,

                goalList: [
                    'Lose 2 lb per week',
                    'Lose 1 lb per week',
                    'Lose 0.5 lb per week',
                    'Maintain weight',
                    'Gain 0.5 lb per week',
                    'Gain 1 lb per week',
                    'Gain 2 lb per week'
                ],
                goalListValues: [
                    -2,
                    -1,
                    -0.5,
                    0,
                    0.5,
                    1,
                    2
                ]
            }
        };

        switch (appVersion) {
            case 'com':
                Vue.prototype.$phrase = phrase.com;
                break;
            default:
                Vue.prototype.$phrase = phrase.coUk;
                break;
        }
    }
};

export default phrases;
