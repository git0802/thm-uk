import phrases from '../prototypes/phrases'

const common = {
    install(Vue) {
        Vue.prototype.$useMetricSystem = (window.laravel && window.laravel.useMetricSystem);

        Vue.prototype.convertCMtoInches = function (cm) {
            cm = this.convertToFloat(cm);

            let inches = cm * 0.393700787;

            if (inches % 1 >= 0.99) {
                inches = Math.round(inches);
            }

            return {
                in: inches,
                ft: Math.trunc(inches / 12),
                inRest: (inches % 12).toFixed(1) * 1,
            };
        };

        Vue.prototype.convertToCentimeters = function (feet, inches) {
            feet = this.convertToFloat(feet);
            inches = this.convertToFloat(inches);

            inches += feet * 12;

            return (inches * 2.54).toFixed(2) * 1;
        };

        Vue.prototype.convertToFloat = function (string, fraction) {
            let val = Number.parseFloat(string);

            if (Number.isNaN(val)) {
                return 0;
            }

            return val.toFixed(fraction ? fraction : 2) * 1;
        };

        Vue.prototype.setWeight = function (value) {
            if (!this.$useMetricSystem) {
                return this.convertToKg(value);
            }

            return value;
        };

        Vue.prototype.getWeight = function (value) {
            if (!this.$useMetricSystem) {
                return this.convertToLb(value);
            }
            
            return value;
        };

        Vue.prototype.convertToKg = function (lb) {
            return this.convertToFloat(this.convertToFloat(lb, 3) * 0.45359237, 3);
        };

        Vue.prototype.convertToLb = function (kg) {
            return this.convertToFloat(this.convertToFloat(kg) * 2.20462262, 1);
        };

        Vue.use(phrases);

        Vue.mixin({
            data: function () {
                return {
                    phrase: Vue.prototype.$phrase
                }
            }
        });
    }
};

export default common;
