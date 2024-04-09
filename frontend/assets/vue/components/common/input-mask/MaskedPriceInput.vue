<template>
    <input :type="type"  @focus="onFocus" @blur="onBlur" @input="onInput" v-model="inputValue" :class="inputClass" :placeholder="procPlaceholder">
    <input type="hidden" :name="name" v-model="hiddenValue">
</template>

<script>
import { vMaska, Mask } from "maska";

/**
 * Инпут для ввода стоимости с маской разрядов и преобразованием числа в сокращенный текст с указанием порядка (тыс., млн., млрд.)
 */
export default {
    name: "MaskedPriceInput",
    directives: { maska: vMaska },
    props: {
        name:  String,
        value: String,
        type:  {default: 'text'},
        placeholder: {default: ''},

        inputClass:  {default: ''},
        prefix:      {default: ''},
        suffix:      {default: ''},
    },


    data() {
        return {

            mask:  new Mask({
                mask: "9 99#",
                reversed: true,
                tokens: {
                    9: {
                        pattern: /[0-9]/,
                        repeated: true
                    }
                }
            }),

            procPlaceholder: null,

            rawValue: null,  // число введенное пользователем
            maskValue: null, // число с маской разряда
            procValue: null, // сокращенная цена с префиксом и суффиксом

            inputValue: null,  // значение для показа
            hiddenValue: null, // значение для отправки на сервер

        };
    },
    methods: {

        onFocus(e) {
            this.maskValue = this.mask.masked(this.rawValue);

            this.inputValue = this.maskValue;
        },

        onInput(e) {
            this.rawValue  = e.target.value.replace(/\D/g,'');
            this.maskValue = this.mask.masked(this.rawValue);

            this.inputValue = this.maskValue;
            this.hiddenValue = this.rawValue;
        },

        onBlur(e) {
            this.procValue = this.processValue(this.rawValue);

            this.inputValue = this.rawValue ? this.procValue : '';
            this.hiddenValue = this.rawValue;
        },

        processValue(val) {
            let num = parseInt(val.replace(/\D/g,''));


            switch (true) {
                case (num <= 99999):
                    num = this.mask.masked(num.toString()).replace(/\./g,',') + ' ';
                    break;
                case (num < 999999):
                    num = ((Math.floor(num / 10) * 10) / 1000).toString().replace(/\./g,',') + ' тыс.';
                    break;
                case (num < 999999999):
                    num = ((Math.floor(num / 10) * 10) / 1000000).toString().replace(/\./g,',') + ' млн.';
                    break;
                case (num < 999999999999):
                    num = ((Math.floor(num / 10) * 10) / 1000000000).toString().replace(/\./g,',') + ' млрд.';
                    break;
            }

            return this.prefix + num + this.suffix;
        },

    },
    computed: {
    },

    mounted() {
        if (this.value) {
            this.rawValue  = this.value;
            this.maskValue = this.mask.masked(this.value);
            this.procValue = this.processValue(this.value);

            this.inputValue = this.procValue;
            this.hiddenValue = this.rawValue;
        }
        if (this.placeholder) {
            this.procPlaceholder = this.processValue(this.placeholder);
        }
    }

}
</script>

<style scoped>

</style>