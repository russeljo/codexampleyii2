<template>
    <div class="popup popup__confirm show" v-if="isVisible">
        <div class="popup__box popup__sidebar">
            <div class="popup__box_close">
                <button class="icon" type=""
                    @click="this.close()"
                ><i class="i i-times"></i>
                </button>
            </div>
            <div class="popup__box_body">
                <div class="popup__box_content">
                    <div class="popup_title" v-html="title"></div>
                </div>
                <div class="popup__box_content">
                    <p v-html="text"></p>
                </div>
                <div class="popup__box_footer">
                    <button class="confirm" type=""
                        @click="this.accept()"
                    >{{yes}}</button>
                    <button class="confirm_second" type="" style="max-width: inherit"
                        @click="this.decline()"
                    >{{no}}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "DialogModal",
    props: {
        title: {
            type: String,
            default: ''
        },
        text: {
            type: String,
            default: ''
        },
        yes: {
            type: String,
            default: 'Да'
        },
        no: {
            type: String,
            default: 'Отмена'
        },
        show: {
            type: Boolean,
            default: false
        },
        declineOnClose: {
            type: Boolean,
            default: false
        },
        acceptCallback: {
            type: Function
        },
        declineCallback: {
            type: Function
        },
    },
    data() {
        return {
            isVisible: false
        }
    },
    watch: {
        show: {
            handler(newVal, oldVal) {
                this.isVisible = newVal
            },
            flush: 'post'
        },
    },
    methods: {
        accept() {
            console.log('Dialog click accept')
            if (this.acceptCallback) {
                this.acceptCallback()
            }

            this.hideModal()
        },
        decline() {
            console.log('Dialog click decline')
            if (this.declineCallback) {
                this.declineCallback()
            }

            this.hideModal()
        },
        /**
         * При нажатии кнопки закрытия - вызываем callBack отмены, если declineOnClose=true
         */
        close() {
            console.log('Dialog click Close')
            if (this.declineOnClose && this.declineCallback) {
                this.declineCallback()
            }

            this.hideModal()
        },

        hideModal() {
            this.isVisible = false
        }
    },
}
</script>

<style scoped>

</style>