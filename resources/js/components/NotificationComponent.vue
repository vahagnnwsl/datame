<template>
    <div>

        <notifications group="message-for-all" position="bottom right"/>

        <notifications group="notification"
                       :duration="5000"
                       :width="400"
                       animation-name="v-fade-left"
                       :speed="200"
                       position="bottom right">

            <template slot="body" slot-scope="props">
                <div class="custom-template">
                    <div class="custom-template-icon">
                        <i class="icon ion-android-checkmark-circle"></i>
                    </div>
                    <div class="custom-template-content">
                        <div class="custom-template-title">
                            {{props.item.title}}
                        </div>
                        <div class="custom-template-text"
                             v-html="props.item.text"></div>
                    </div>
                    <div class="custom-template-close"
                         @click="props.close">
                        <i class="icon ion-android-close"></i>
                    </div>
                </div>
            </template>
        </notifications>


    </div>
</template>

<script>
    export default {
        name: "NotificationComponent"
    }
</script>

<style scoped>

    .custom-template {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        text-align: left;
        font-size: 13px;
        margin: 5px 5px 0;
        align-items: center;
        justify-content: center;
        background: #e8f9f0;
        border: 2px solid #d0f2e1;
    }

    .custom-template, .custom-template > div {
        box-sizing: border-box;
    }

    .custom-template .custom-template-icon {
        flex: 0 1 auto;
        color: #15c371;
        font-size: 32px;
        padding: 0 10px;
    }

    .custom-template .custom-template-close {
        flex: 0 1 auto;
        padding: 0 20px;
        font-size: 16px;
        opacity: 0.2;
        cursor: pointer;
    }

    .custom-template .custom-template-close:hover {
        opacity: 0.8;
    }

    .custom-template .custom-template-content {
        padding: 10px;
        flex: 1 0 auto;
    }

    .custom-template .custom-template-content .custom-template-title {
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 10px;
        font-weight: 600;
    }

</style>