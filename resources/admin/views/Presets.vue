<template>
    <v-layout column>
        <overlay-component v-if="isBusy"/>

        <v-flex class="xs12 pa-2">
            <div style="display: inline-flex; justify-content: space-between; width: 100%">
                <h1>
                    Planner Presets
                </h1>
                <v-btn color="success" @click="createDialog = !createDialog">
                    New preset
                </v-btn>

            </div>
            <v-divider/>
        </v-flex>
        <v-layout row>
            <v-flex class="col-sm-2">
                <v-list two-line>
                    <v-subheader>PRESETS</v-subheader>
                    <v-list-item-group
                        v-model="selected"
                        active-class="blue--text"
                    >
                        <template v-for="(item, index) in presets">
                            <v-list-item :key="item.title">
                                <template v-slot:default="{ active }">
                                    <v-list-item-content>
                                        <v-list-item-title v-text="item.name"/>
                                        <v-list-item-subtitle v-text="item.description"/>
                                    </v-list-item-content>

                                    <v-list-item-action v-if="item.store">
                                        <v-list-item-action-text v-text="item.store.name"/>

                                        <v-list-item-avatar style="margin: unset">
                                            <v-img :src="item.store.image"/>
                                        </v-list-item-avatar>

                                        <v-list-item-action-text>
                                            <template v-if="item.is_published">
                                                <v-chip x-small color="orange">
                                                    Published
                                                </v-chip>
                                            </template>
                                        </v-list-item-action-text>

                                    </v-list-item-action>
                                </template>
                            </v-list-item>

                            <v-divider
                                v-if="index < presets.length - 1"
                                :key="index"
                            ></v-divider>
                        </template>
                    </v-list-item-group>
                </v-list>


            </v-flex>
            <v-flex class="col-sm-6">
                <v-layout column>
                    <template v-if="selected !== undefined && preset">
                        <v-flex class="xs12">
                            <div
                                style="display: inline-flex; justify-content: space-between; width: 100%; padding-bottom: 5px;">
                                <h2>
                                    {{ preset.name }}
                                </h2>
                                <div>
                                    <v-btn
                                        :disabled="csv.loading"
                                        :loading="csv.loading"
                                        @click="csv.modal = !csv.modal"
                                    >
                                        STORE CSV
                                    </v-btn>
                                    <v-btn color="success"
                                           @click="publishPreset"
                                           :disabled="preset.is_published">
                                        Publish preset
                                    </v-btn>
                                    <v-btn color="info" @click="openEditDialog">
                                        Edit
                                    </v-btn>
                                    <v-btn color="error" @click="deletePreset">
                                        Delete
                                    </v-btn>

                                </div>
                            </div>
                        </v-flex>
                        <v-flex class="xs12">
                            <span>
                                {{ preset.description }}
                            </span>
                        </v-flex>

                        <v-flex class="xs12">
                            <v-tabs
                                v-model="selectedDay"
                            >
                                <template v-for="(items, key) in preset['preset']">
                                    <v-tab>
                                        {{ key }}
                                    </v-tab>
                                </template>
                            </v-tabs>
                        </v-flex>

                        <v-flex class="xs12 sticky-head-total">
                            Week total calories:
                            {{ totals.calories }} || Week total cost: {{ totals.cost.toFixed(2) }} £
                        </v-flex>
                        <v-flex class="xs12 sticky-head-day">

                            <template v-if="selectedDay !== null  && weekdays">
                                Day total calories: {{ totals[weekdays[selectedDay].toLowerCase()].calories }}
                                <template v-if="totals[weekdays[selectedDay].toLowerCase()].calories < 1000"><span
                                    style="color: red">(Below 1000 calories!)</span>
                                </template>
                                || Day total cost: {{ totals[weekdays[selectedDay].toLowerCase()].cost.toFixed(2) }} £
                            </template>

                        </v-flex>


                        <v-flex class="xs12">
                            <template v-for="(items, eating) in presetData">


                                <div style="position: relative; min-height: 15vh">
                                    <div class="subtitle-1 flex row justify-space-between sticky-small-stats">
                                        <div>
                                            {{ eating }} | {{ items.length }} item(s)
                                        </div>
                                        <div>
                                            Calories: {{ totals[weekdays[selectedDay]][eating].calories }} | Cost:
                                            {{ totals[weekdays[selectedDay]][eating].cost.toFixed(2) }} £
                                        </div>
                                    </div>
                                    <template v-for="item in items">
                                        <template v-if="item.product.is_dish">
                                            <v-row dense>
                                                <v-lazy width="100%">
                                                    <v-col cols="12" class="pb-2 pt-2">
                                                        <v-card
                                                            color="#e7f7ff2b"

                                                        >
                                                            <div class="d-flex flex-no-wrap justify-space-between">
                                                                <div style="width:100%">
                                                                    <v-card-title class="pt-2 pb-2">
                                                                        {{ item.product.name }}

                                                                        <a v-if="item.product.url" :href="item.product.url"
                                                                           target="_blank"
                                                                           class="food__links food__links-group ml-2">
                                                                            <svg width="16" height="16" viewBox="0 0 16 16"
                                                                                 fill="none"
                                                                                 xmlns="http://www.w3.org/2000/svg"
                                                                                 xmlns:xlink="http://www.w3.org/1999/xlink">
                                                                                <mask id="clip32" mask-type="alpha"
                                                                                      maskUnits="userSpaceOnUse" x="0" y="0"
                                                                                      width="16" height="16">
                                                                                    <rect width="16" height="16"
                                                                                          transform="matrix(1 0 0 -1 0 16)"
                                                                                          fill="url(#pattern0)"/>
                                                                                </mask>
                                                                                <g mask="url(#clip32)">
                                                                                    <rect width="16" height="16"
                                                                                          fill="#df2c65"/>
                                                                                </g>
                                                                                <defs>
                                                                                    <pattern id="pattern0"
                                                                                             patternContentUnits="objectBoundingBox"
                                                                                             width="1"
                                                                                             height="1">
                                                                                        <use xlink:href="#image0"
                                                                                             transform="scale(0.02)"/>
                                                                                    </pattern>
                                                                                    <image id="image0" width="50"
                                                                                           height="50"
                                                                                           xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAADqklEQVRogc2aS0hUURjHf6MSgb3TJIgWhigRUYtCyxZWUJSRBT1sVdSux0oQQsggKKJo0QNqE9EmbFmQRIuitBeaaZsiioSiiCkykl6MLc5M3b57zvXemW/mzh/u5p7v/v/ff+55fedOgmA0As1AEzAHqAQ+Ae+BXuA6cBP4PQ5PbFgJ3AfGQlwvgFYgEUumDiSATiBFOBPeqwsoL3jGFpQAV4luwHv1Y7pfrDiKPbkBoA1YDtQADcBeoMcRP0iMZlbi706jwC7Mm3KhBUhSRGbkwB4F6kM+WwMMUwRmGi1J7IzIUU0RmDmGf0wEdScXqoE3xGjmoRBuy4ErVjNvhejyHPliM/NDCNYqcMZi5oMQCztbjYeCmxkQQvsUuYPMTFPUAeCsEOlR5neZuQGUagqts4hs1BTAbaZDU6QMsxX3CiQxK7YmqoF3QmcEqNAUacX/aw2nxTVRD/wSOp2aAglMPaFlpho4CGzFX3BdEBpPskvZjXJMPSHNvCGamS2YTWfm+YOifbHgTwFVuSRuQyVmaszWzGb85YCcBROYut8bs0Qhdx+yNbMQ+Gp57rgldkjENHvaSlFcMLMxc88SfwYzK0q8EnFN6fu1wOv0vVvA1NytRDNjW4tOOninAD9FbF267aK4/wClHUCQmVmeOHloMYh71d4hYj/z763JGW0MeJRvM4fS7ROAL6Jtm4OrDHgmYq942udirzQfA9PzZaY93TZf3P8BTHLwnLIkuUbEuMrmPmCGlpluTP/uBian768Sgi8tz07AvzkdA+44tFxm+oGZuVuxY4MQG7LEXLIk9RVYEMDrMjOA8v4sg6VCKIl/W/JNxKSATSG4XWaekofirAr/ar5IxNzytI0QzkQGQd1M/axZVpnnRftM4ARwjn9rRhS4zHSh/BXgsBD4hV7tn4HLzHZNkQpMl/EKvEO/nqnBf9b8HPs2KGt04P+1opYAYdBi0VmrKVCKOVAohJleoXFamZ9p5FbPhMUBwd+nyP0Xrr3ZMDBPSWOZ4H6vxOtDrpXmeKgTvN8VOJ3Ip5kV+N92XpEvM+2Crze3NMNB20yJhe+ISqYhEDQBRDWz28KjvYsIRJCZsMezDfx/ZjYG3FXPNARcZpKYFduFEmAPfhMpzMCPBS4zmUG7H7NO1GKSbA+IL9jYcKES+/FslKuL7L4+q2MicJnoBlKYT+pFYSKDBKaeeE44E3dxjIli+Y9VGbAaWI/5PD4bc471ETOr3QauYU4erfgDd4VqDQ3HH5wAAAAASUVORK5CYII="/>
                                                                                </defs>
                                                                            </svg>
                                                                        </a>

                                                                    </v-card-title>
                                                                    <div class="fooz">
                                                                        <div class="foos">
                                                                            <div class="title">
                                                                                Store
                                                                            </div>
                                                                            <div class="text">

                                                                                {{ findStoreById(item.product.store_id) }}

                                                                            </div>
                                                                        </div>

                                                                        <div class="foos">
                                                                            <div class="title">
                                                                                Serving Size
                                                                            </div>
                                                                            <div class="text">
                                                                                {{ item.product.serving_size }}
                                                                            </div>
                                                                        </div>
                                                                        <div class="foos">
                                                                            <div class="title">
                                                                                Servings
                                                                            </div>
                                                                            <div class="text">

                                                                                <div class="qty">
                                                                                    <button type="button" class="qty-btn"
                                                                                            @click="decreaseQuantity(item.product.id, eating, selectedDay, item.servings)">
                                                                                        <span>-</span>
                                                                                    </button>
                                                                                    <span class="qty-number">{{
                                                                                            item.servings
                                                                                        }}</span>
                                                                                    <button type="button" class="qty-btn"
                                                                                            @click="increaseQuantity(item.product.id, eating, selectedDay, item.servings)">
                                                                                        <span>+</span>
                                                                                    </button>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                        <div class="foos">
                                                                            <div class="title">
                                                                                Calories
                                                                            </div>
                                                                            <div class="text">
                                                                                {{ item.product.calories * item.servings }}
                                                                            </div>
                                                                        </div>
                                                                        <div class="foos">
                                                                            <div class="title">
                                                                                Cost
                                                                            </div>
                                                                            <div class="text">
                                                                                {{ item.product.cost.toFixed(2) }} £
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                </div>

                                                                <div style="display: inline-grid;">
                                                                    <v-avatar
                                                                        class="ma-3"
                                                                        size="84"
                                                                        tile
                                                                    >
                                                                        <figure class="food-block__group-img">
                                                                            <template v-for="(product, index) in item.product.dish">
                                                                                <img :key="index"
                                                                                     onerror="this.src='/images/no-product-image.jpg?t=0'"
                                                                                     v-if="index <= 3"
                                                                                     :alt="product.name  + ' ' + index"
                                                                                     :src="product.image"
                                                                                />
                                                                            </template>
                                                                        </figure>
                                                                    </v-avatar>
                                                                    <v-btn
                                                                        color="error"
                                                                        class="delete-button--absolute"

                                                                        x-small
                                                                        @click="deleteProductFromPreset(item.product.id, eating, weekdays[selectedDay])"
                                                                    >
                                                                        DELETE
                                                                    </v-btn>
                                                                </div>

                                                            </div>

                                                            <div class="food-block__show food-block__show--colums food-override-style">
                                                                <template v-for="dsh in item.product.dish">
                                                                    <div class="food-group__wrap">

                                                                        <div class="food-group__block">
                                                                            <img
                                                                                :src="dsh.image"
                                                                                :alt="dsh.name"
                                                                                onerror="this.src='/images/no-product-image.jpg?t=0'"
                                                                            >
                                                                            <div class="food-group__block-group">
                                                                <span class="name"><a v-if="dsh.url" :href="dsh.url" target="_blank"
                                                                                      class="food__links food__links-solo">
                                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                                         xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M3.253 6.61464L1.27006 8.59758C0.456853 9.41078 0 10.5137 0 11.6638C0 12.8138 0.456853 13.9168 1.27006 14.73C2.08326 15.5432 3.1862 16 4.33625 16C5.48629 16 6.58923 15.5432 7.40243 14.73L10.0444 12.0865C10.5348 11.5961 10.9004 10.9951 11.1105 10.3342C11.3205 9.6732 11.369 8.97148 11.2518 8.2879C11.1345 7.60433 10.855 6.95886 10.4367 6.40565C10.0184 5.85245 9.4735 5.40767 8.84773 5.10864L8.00079 5.95558C7.91479 6.04171 7.84001 6.13835 7.77821 6.2432C8.2617 6.38219 8.70054 6.64496 9.05136 7.00553C9.40217 7.3661 9.65281 7.81198 9.7785 8.2991C9.90418 8.78622 9.90056 9.2977 9.76799 9.78299C9.63542 10.2683 9.37849 10.7106 9.02261 11.0661L6.38206 13.7081C5.83967 14.2505 5.10403 14.5552 4.33697 14.5552C3.56991 14.5552 2.83427 14.2505 2.29188 13.7081C1.74949 13.1657 1.44477 12.4301 1.44477 11.663C1.44477 10.896 1.74949 10.1603 2.29188 9.61795L3.438 8.47328C3.27629 7.86731 3.21381 7.23914 3.253 6.61319V6.61464Z"
                                                                            fill="#DF2C65"/>
                                                                        <path
                                                                            d="M4.69338 7.21877L4.93474 6.9774C5.29282 6.618 5.73959 6.35971 6.22972 6.22874C6.3607 5.7386 6.61898 5.29183 6.97838 4.93376L7.21975 4.69239C6.49272 4.65152 5.76708 4.79416 5.10962 5.10719C4.79166 5.77203 4.65291 6.49901 4.69338 7.21732V7.21877Z"
                                                                            fill="#DF2C65"/>
                                                                        <path
                                                                            d="M5.95723 3.91205C5.46685 4.4025 5.10128 5.00343 4.8912 5.6644C4.68112 6.32537 4.63266 7.02709 4.74989 7.71066C4.86712 8.39424 5.14663 9.03971 5.56494 9.59291C5.98325 10.1461 6.52816 10.5909 7.15393 10.8899L8.27404 9.76838C7.78401 9.63694 7.3372 9.37884 6.97853 9.02001C6.61985 8.66118 6.36193 8.21427 6.2307 7.72419C6.09946 7.2341 6.09953 6.71811 6.23089 6.22806C6.36225 5.73801 6.62028 5.29116 6.97905 4.93243L9.6196 2.29043C10.162 1.74804 10.8976 1.44333 11.6647 1.44333C12.4317 1.44333 13.1674 1.74804 13.7098 2.29043C14.2522 2.83283 14.5569 3.56847 14.5569 4.33552C14.5569 5.10258 14.2522 5.83822 13.7098 6.38061L12.5637 7.52528C12.7255 8.13231 12.7877 8.76101 12.7487 9.38538L14.7316 7.40243C15.5448 6.58923 16.0017 5.48629 16.0017 4.33625C16.0017 3.1862 15.5448 2.08326 14.7316 1.27006C13.9184 0.456853 12.8155 0 11.6654 0C10.5154 0 9.41243 0.456853 8.59923 1.27006L5.95723 3.91205Z"
                                                                            fill="#DF2C65"/>
                                                                        <path
                                                                            d="M10.8924 10.8897C11.2067 10.2326 11.3499 9.50676 11.3086 8.77954L11.0672 9.0209C10.7092 9.3803 10.2624 9.63859 9.77225 9.76957C9.64128 10.2597 9.38299 10.7065 9.02359 11.0645L8.78223 11.3059C9.50926 11.3468 10.2349 11.2041 10.8924 10.8911V10.8897Z"
                                                                            fill="#DF2C65"/>
                                                                    </svg>
                                                                </a>
                                                                     <v-tooltip top>
                                                                         <template v-slot:activator="{ on, attrs }">
                                                                             <span
                                                                                 v-bind="attrs"
                                                                                 v-on="on"
                                                                             >
                                                                                  {{ dsh.name }}

                                                                             </span>
                                                                         </template>
                                                                         <span>
                                                                              {{ dsh.name }}
                                                                         </span>
                                                                     </v-tooltip>
                                                                </span>
                                                                            </div>
                                                                            <span class="text"> {{ findStoreById(dsh.store_id) }} </span>
                                                                            <span class="text">{{ dsh.serving_size }}</span>
                                                                            <span class="text">{{ dsh.calories }}</span>
                                                                            <span class="text">£ {{ dsh.cost.toFixed(2) }}</span>
                                                                        </div>
                                                                    </div>

                                                                </template>
                                                            </div>



                                                        </v-card>

                                                    </v-col>
                                                </v-lazy>

                                            </v-row>

                                        </template>

                                        <template v-else>
                                            <v-row dense>
                                                <v-lazy width="100%">
                                                    <v-col cols="12" class="pb-2 pt-2">
                                                        <v-card
                                                            color="#e7f7ff2b"

                                                        >
                                                            <div class="d-flex flex-no-wrap justify-space-between">
                                                                <div style="width:100%">
                                                                    <v-card-title class="pt-2 pb-2">
                                                                        {{ item.product.name }}

                                                                        <a v-if="item.product.url" :href="item.product.url"
                                                                           target="_blank"
                                                                           class="food__links food__links-group ml-2">
                                                                            <svg width="16" height="16" viewBox="0 0 16 16"
                                                                                 fill="none"
                                                                                 xmlns="http://www.w3.org/2000/svg"
                                                                                 xmlns:xlink="http://www.w3.org/1999/xlink">
                                                                                <mask id="clip32" mask-type="alpha"
                                                                                      maskUnits="userSpaceOnUse" x="0" y="0"
                                                                                      width="16" height="16">
                                                                                    <rect width="16" height="16"
                                                                                          transform="matrix(1 0 0 -1 0 16)"
                                                                                          fill="url(#pattern0)"/>
                                                                                </mask>
                                                                                <g mask="url(#clip32)">
                                                                                    <rect width="16" height="16"
                                                                                          fill="#df2c65"/>
                                                                                </g>
                                                                                <defs>
                                                                                    <pattern id="pattern0"
                                                                                             patternContentUnits="objectBoundingBox"
                                                                                             width="1"
                                                                                             height="1">
                                                                                        <use xlink:href="#image0"
                                                                                             transform="scale(0.02)"/>
                                                                                    </pattern>
                                                                                    <image id="image0" width="50"
                                                                                           height="50"
                                                                                           xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAADqklEQVRogc2aS0hUURjHf6MSgb3TJIgWhigRUYtCyxZWUJSRBT1sVdSux0oQQsggKKJo0QNqE9EmbFmQRIuitBeaaZsiioSiiCkykl6MLc5M3b57zvXemW/mzh/u5p7v/v/ff+55fedOgmA0As1AEzAHqAQ+Ae+BXuA6cBP4PQ5PbFgJ3AfGQlwvgFYgEUumDiSATiBFOBPeqwsoL3jGFpQAV4luwHv1Y7pfrDiKPbkBoA1YDtQADcBeoMcRP0iMZlbi706jwC7Mm3KhBUhSRGbkwB4F6kM+WwMMUwRmGi1J7IzIUU0RmDmGf0wEdScXqoE3xGjmoRBuy4ErVjNvhejyHPliM/NDCNYqcMZi5oMQCztbjYeCmxkQQvsUuYPMTFPUAeCsEOlR5neZuQGUagqts4hs1BTAbaZDU6QMsxX3CiQxK7YmqoF3QmcEqNAUacX/aw2nxTVRD/wSOp2aAglMPaFlpho4CGzFX3BdEBpPskvZjXJMPSHNvCGamS2YTWfm+YOifbHgTwFVuSRuQyVmaszWzGb85YCcBROYut8bs0Qhdx+yNbMQ+Gp57rgldkjENHvaSlFcMLMxc88SfwYzK0q8EnFN6fu1wOv0vVvA1NytRDNjW4tOOninAD9FbF267aK4/wClHUCQmVmeOHloMYh71d4hYj/z763JGW0MeJRvM4fS7ROAL6Jtm4OrDHgmYq942udirzQfA9PzZaY93TZf3P8BTHLwnLIkuUbEuMrmPmCGlpluTP/uBian768Sgi8tz07AvzkdA+44tFxm+oGZuVuxY4MQG7LEXLIk9RVYEMDrMjOA8v4sg6VCKIl/W/JNxKSATSG4XWaekofirAr/ar5IxNzytI0QzkQGQd1M/axZVpnnRftM4ARwjn9rRhS4zHSh/BXgsBD4hV7tn4HLzHZNkQpMl/EKvEO/nqnBf9b8HPs2KGt04P+1opYAYdBi0VmrKVCKOVAohJleoXFamZ9p5FbPhMUBwd+nyP0Xrr3ZMDBPSWOZ4H6vxOtDrpXmeKgTvN8VOJ3Ip5kV+N92XpEvM+2Crze3NMNB20yJhe+ISqYhEDQBRDWz28KjvYsIRJCZsMezDfx/ZjYG3FXPNARcZpKYFduFEmAPfhMpzMCPBS4zmUG7H7NO1GKSbA+IL9jYcKES+/FslKuL7L4+q2MicJnoBlKYT+pFYSKDBKaeeE44E3dxjIli+Y9VGbAaWI/5PD4bc471ETOr3QauYU4erfgDd4VqDQ3HH5wAAAAASUVORK5CYII="/>
                                                                                </defs>
                                                                            </svg>
                                                                        </a>

                                                                    </v-card-title>
                                                                    <div class="fooz">
                                                                        <div class="foos">
                                                                            <div class="title">
                                                                                Store
                                                                            </div>
                                                                            <div class="text">

                                                                                {{ findStoreById(item.product.store_id) }}

                                                                            </div>
                                                                        </div>

                                                                        <div class="foos">
                                                                            <div class="title">
                                                                                Serving Size
                                                                            </div>
                                                                            <div class="text">
                                                                                {{ item.product.serving_size }}
                                                                            </div>
                                                                        </div>
                                                                        <div class="foos">
                                                                            <div class="title">
                                                                                Servings
                                                                            </div>
                                                                            <div class="text">

                                                                                <div class="qty">
                                                                                    <button type="button" class="qty-btn"
                                                                                            @click="decreaseQuantity(item.product.id, eating, selectedDay, item.servings)">
                                                                                        <span>-</span>
                                                                                    </button>
                                                                                    <span class="qty-number">{{
                                                                                            item.servings
                                                                                        }}</span>
                                                                                    <button type="button" class="qty-btn"
                                                                                            @click="increaseQuantity(item.product.id, eating, selectedDay, item.servings)">
                                                                                        <span>+</span>
                                                                                    </button>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                        <div class="foos">
                                                                            <div class="title">
                                                                                Calories
                                                                            </div>
                                                                            <div class="text">
                                                                                {{ item.product.calories * item.servings }}
                                                                            </div>
                                                                        </div>
                                                                        <div class="foos">
                                                                            <div class="title">
                                                                                Cost
                                                                            </div>
                                                                            <div class="text">
                                                                                {{ item.product.cost.toFixed(2) }} £
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                </div>

                                                                <div style="display: inline-grid;">
                                                                    <v-avatar
                                                                        class="ma-3"
                                                                        size="84"
                                                                        tile
                                                                    >
                                                                        <v-img :src="item.product.image"
                                                                               onerror="this.src='/images/no-product-image.jpg?t=0'"></v-img>
                                                                    </v-avatar>
                                                                    <v-btn
                                                                        color="error"
                                                                        class="delete-button--absolute"
                                                                        x-small
                                                                        @click="deleteProductFromPreset(item.product.id, eating, weekdays[selectedDay])"
                                                                    >
                                                                        DELETE
                                                                    </v-btn>
                                                                </div>

                                                            </div>


                                                        </v-card>

                                                    </v-col>
                                                </v-lazy>

                                            </v-row>

                                        </template>
                                    </template>
                                </div>
                            </template>
                        </v-flex>


                    </template>

                    <template v-else>
                        <h2>
                            Preset not selected
                        </h2>
                    </template>

                </v-layout>
            </v-flex>
            <v-flex class="col-sm-4">
                <div class="sticky-store-products">
                    <v-subheader>
                        STORE PRODUCTS
                        <ProductGrouping v-if="selectedPreset" :store_id="productAdder.selectedStore"
                                         @dishAdded="addDishToList"/>

                    </v-subheader>
                    <template v-if="selected === undefined">
                        <h4>
                            Pick preset to continue
                        </h4>
                    </template>

                    <template v-else>
                        <div>
                            <v-select
                                v-model="productAdder.selectedStore"
                                :items="stores"
                                item-value="id"
                                item-text="name"
                                label="Select store"
                            />
                            <template v-if="productAdder.selectedStore">
                                <v-autocomplete
                                    @update:search-input="appendSearchInput"
                                    :search-input="productAdder.search"
                                    v-model="productAdder.testModel"
                                    :items="productAdder.products"
                                    :loading="productAdder.isLoading"
                                    item-text="name"
                                    item-value="id"
                                    label="Product search"
                                    placeholder="Start typing to Search"
                                    prepend-icon="mdi-database-search"
                                    hide-no-data
                                    return-object
                                    clearable
                                />
                            </template>
                        </div>
                    </template>

                    <div>
                        <v-list two-line>
                            <v-subheader>ITEMS</v-subheader>
                            <v-list>

                                <template v-if="preset['preset']" v-for="(item, index) in productAdder.entries">
                                    {{item.is}}
                                    <template v-if="item.is_dish">
                                        <v-lazy width="100%" class="item-bordered">
                                            <div>
                                                <v-list-item :key="item.id" three-line>
                                                    <template>
                                                        <v-list-item-content>
                                                            <v-list-item-title style="color: black !important;"
                                                                               v-text="item.name"/>
                                                            <v-list-item-subtitle>
                                                                <div class="fooz"
                                                                     style="padding-top: 15px; margin-bottom: unset">
                                                                    <div class="foos foos-4 ">
                                                                        <div class="title">
                                                                            Serving Size
                                                                        </div>
                                                                        <div class="text">
                                                                            {{ item.serving_size }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="foos foos-4 " v-if="item.package_size">
                                                                        <div class="title">
                                                                            Package Size
                                                                        </div>
                                                                        <div class="text">
                                                                            {{ item.package_size }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="foos foos-4 ">
                                                                        <div class="title">
                                                                            Calories
                                                                        </div>
                                                                        <div class="text">
                                                                            {{ item.calories }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="foos foos-4 ">
                                                                        <div class="title">
                                                                            Cost
                                                                        </div>
                                                                        <div class="text">
                                                                            {{ item.cost.toFixed(2) }}£
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                            </v-list-item-subtitle>
                                                            <v-list-item-subtitle>
                                                                <div
                                                                    style="display: flex; justify-content: space-between; padding-top: 5px;">
                                                                    <v-menu>

                                                                        <template v-slot:activator="{ on, attrs }">
                                                                            <v-btn
                                                                                x-small
                                                                                @click=""
                                                                                style="margin-bottom: 2px;"
                                                                                color="info"
                                                                                v-bind="attrs"
                                                                                v-on="on"
                                                                            >
                                                                                ADD TO

                                                                            </v-btn>
                                                                        </template>

                                                                        <v-list>
                                                                            <v-list-item link
                                                                                         @click="appendProductToEating('breakfast', item.id)">
                                                                                <v-list-item-title>Add to Breakfast
                                                                                </v-list-item-title>
                                                                            </v-list-item>
                                                                            <v-list-item link
                                                                                         @click="appendProductToEating('lunch', item.id)">
                                                                                <v-list-item-title>Add to Lunch
                                                                                </v-list-item-title>
                                                                            </v-list-item>
                                                                            <v-list-item link
                                                                                         @click="appendProductToEating('dinner', item.id)">
                                                                                <v-list-item-title>Add to Dinner
                                                                                </v-list-item-title>
                                                                            </v-list-item>
                                                                            <v-list-item link
                                                                                         @click="appendProductToEating('snacks', item.id)">
                                                                                <v-list-item-title>Add to Snacks
                                                                                </v-list-item-title>
                                                                            </v-list-item>
                                                                        </v-list>

                                                                    </v-menu>


                                                                    <v-btn
                                                                        color="error"
                                                                        x-small
                                                                        @click="deleteSelectedProduct(item.id)"
                                                                    >
                                                                        REMOVE
                                                                    </v-btn>
                                                                </div>

                                                            </v-list-item-subtitle>

                                                        </v-list-item-content>
                                                        <v-list-item-action>
                                                            <v-list-item-action-text v-text="find(stores, function(e) {
                                                       return e.id === item.store_id
                                                     }).name"/>

                                                            <v-list-item-avatar
                                                                style="height: 58px; min-width: 58px; width: 58px; margin: unset; border-radius: unset;">
                                                                <figure class="food-block__group-img">
                                                                    <template v-for="(product, index) in item.dish">
                                                                        <img :key="index"
                                                                             onerror="this.src='/images/no-product-image.jpg?t=0'"
                                                                             v-if="index <= 3"
                                                                             :alt="product.name  + ' ' + index"
                                                                             :src="product.image"
                                                                        />
                                                                    </template>
                                                                </figure>
                                                            </v-list-item-avatar>
                                                        </v-list-item-action>
                                                    </template>
                                                </v-list-item>

                                                <div class="food-block__show food-block__show--colums food-override-style">
                                                    <template v-for="dsh in item.dish">
                                                        <div class="food-group__wrap">

                                                            <div class="food-group__block">
                                                                <img
                                                                    :src="dsh.image"
                                                                    :alt="dsh.name"
                                                                    onerror="this.src='/images/no-product-image.jpg?t=0'"
                                                                >
                                                                <div class="food-group__block-group food-group__block-group--trunc">
                                                                <span class="name"><a v-if="dsh.url" :href="dsh.url" target="_blank"
                                                                                      class="food__links food__links-solo">
                                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                                         xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M3.253 6.61464L1.27006 8.59758C0.456853 9.41078 0 10.5137 0 11.6638C0 12.8138 0.456853 13.9168 1.27006 14.73C2.08326 15.5432 3.1862 16 4.33625 16C5.48629 16 6.58923 15.5432 7.40243 14.73L10.0444 12.0865C10.5348 11.5961 10.9004 10.9951 11.1105 10.3342C11.3205 9.6732 11.369 8.97148 11.2518 8.2879C11.1345 7.60433 10.855 6.95886 10.4367 6.40565C10.0184 5.85245 9.4735 5.40767 8.84773 5.10864L8.00079 5.95558C7.91479 6.04171 7.84001 6.13835 7.77821 6.2432C8.2617 6.38219 8.70054 6.64496 9.05136 7.00553C9.40217 7.3661 9.65281 7.81198 9.7785 8.2991C9.90418 8.78622 9.90056 9.2977 9.76799 9.78299C9.63542 10.2683 9.37849 10.7106 9.02261 11.0661L6.38206 13.7081C5.83967 14.2505 5.10403 14.5552 4.33697 14.5552C3.56991 14.5552 2.83427 14.2505 2.29188 13.7081C1.74949 13.1657 1.44477 12.4301 1.44477 11.663C1.44477 10.896 1.74949 10.1603 2.29188 9.61795L3.438 8.47328C3.27629 7.86731 3.21381 7.23914 3.253 6.61319V6.61464Z"
                                                                            fill="#DF2C65"/>
                                                                        <path
                                                                            d="M4.69338 7.21877L4.93474 6.9774C5.29282 6.618 5.73959 6.35971 6.22972 6.22874C6.3607 5.7386 6.61898 5.29183 6.97838 4.93376L7.21975 4.69239C6.49272 4.65152 5.76708 4.79416 5.10962 5.10719C4.79166 5.77203 4.65291 6.49901 4.69338 7.21732V7.21877Z"
                                                                            fill="#DF2C65"/>
                                                                        <path
                                                                            d="M5.95723 3.91205C5.46685 4.4025 5.10128 5.00343 4.8912 5.6644C4.68112 6.32537 4.63266 7.02709 4.74989 7.71066C4.86712 8.39424 5.14663 9.03971 5.56494 9.59291C5.98325 10.1461 6.52816 10.5909 7.15393 10.8899L8.27404 9.76838C7.78401 9.63694 7.3372 9.37884 6.97853 9.02001C6.61985 8.66118 6.36193 8.21427 6.2307 7.72419C6.09946 7.2341 6.09953 6.71811 6.23089 6.22806C6.36225 5.73801 6.62028 5.29116 6.97905 4.93243L9.6196 2.29043C10.162 1.74804 10.8976 1.44333 11.6647 1.44333C12.4317 1.44333 13.1674 1.74804 13.7098 2.29043C14.2522 2.83283 14.5569 3.56847 14.5569 4.33552C14.5569 5.10258 14.2522 5.83822 13.7098 6.38061L12.5637 7.52528C12.7255 8.13231 12.7877 8.76101 12.7487 9.38538L14.7316 7.40243C15.5448 6.58923 16.0017 5.48629 16.0017 4.33625C16.0017 3.1862 15.5448 2.08326 14.7316 1.27006C13.9184 0.456853 12.8155 0 11.6654 0C10.5154 0 9.41243 0.456853 8.59923 1.27006L5.95723 3.91205Z"
                                                                            fill="#DF2C65"/>
                                                                        <path
                                                                            d="M10.8924 10.8897C11.2067 10.2326 11.3499 9.50676 11.3086 8.77954L11.0672 9.0209C10.7092 9.3803 10.2624 9.63859 9.77225 9.76957C9.64128 10.2597 9.38299 10.7065 9.02359 11.0645L8.78223 11.3059C9.50926 11.3468 10.2349 11.2041 10.8924 10.8911V10.8897Z"
                                                                            fill="#DF2C65"/>
                                                                    </svg>
                                                                </a>
                                                                     <v-tooltip top>
                                                                         <template v-slot:activator="{ on, attrs }">
                                                                             <span
                                                                                 v-bind="attrs"
                                                                                 v-on="on"
                                                                             >
                                                                                  {{ dsh.name }}

                                                                             </span>
                                                                         </template>
                                                                         <span>
                                                                              {{ dsh.name }}
                                                                         </span>
                                                                     </v-tooltip>
                                                                </span>
                                                                </div>
                                                                <span class="text">{{ dsh.store.name }}</span>
                                                                <span class="text">{{ dsh.serving_size }}</span>
                                                                <span class="text">{{ dsh.calories }}</span>
                                                                <span class="text">£ {{ dsh.cost.toFixed(2) }}</span>
                                                            </div>
                                                        </div>

                                                    </template>
                                                </div>
                                            </div>
                                        </v-lazy>
                                    </template>

                                    <template v-else>
                                        <v-list-item :key="item.id" three-line class="item-bordered">
                                            <template>
                                                <v-list-item-content>
                                                    <v-list-item-title style="color: black !important;"
                                                                       v-text="item.name"/>
                                                    <v-list-item-subtitle>
                                                        <div class="fooz"
                                                             style="padding-top: 15px; margin-bottom: unset">
                                                            <div class="foos foos-4 ">
                                                                <div class="title">
                                                                    Serving Size
                                                                </div>
                                                                <div class="text">
                                                                    {{ item.serving_size }}
                                                                </div>
                                                            </div>
                                                            <div class="foos foos-4 " v-if="item.package_size">
                                                                <div class="title">
                                                                    Package Size
                                                                </div>
                                                                <div class="text">
                                                                    {{ item.package_size }}
                                                                </div>

                                                            </div>
                                                            <div class="foos foos-4 ">
                                                                <div class="title">
                                                                    Calories
                                                                </div>
                                                                <div class="text">
                                                                    {{ item.calories }}
                                                                </div>
                                                            </div>
                                                            <div class="foos foos-4 ">
                                                                <div class="title">
                                                                    Cost
                                                                </div>
                                                                <div class="text">
                                                                    {{ item.cost.toFixed(2) }}£
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </v-list-item-subtitle>
                                                    <v-list-item-subtitle>
                                                        <div
                                                            style="display: flex; justify-content: space-between; padding-top: 5px;">
                                                            <v-menu>

                                                                <template v-slot:activator="{ on, attrs }">
                                                                    <v-btn
                                                                        x-small
                                                                        @click=""
                                                                        style="margin-bottom: 2px;"
                                                                        color="info"
                                                                        v-bind="attrs"
                                                                        v-on="on"
                                                                    >
                                                                        ADD TO

                                                                    </v-btn>
                                                                </template>

                                                                <v-list>
                                                                    <v-list-item link
                                                                                 @click="appendProductToEating('breakfast', item.id)">
                                                                        <v-list-item-title>Add to Breakfast
                                                                        </v-list-item-title>
                                                                    </v-list-item>
                                                                    <v-list-item link
                                                                                 @click="appendProductToEating('lunch', item.id)">
                                                                        <v-list-item-title>Add to Lunch
                                                                        </v-list-item-title>
                                                                    </v-list-item>
                                                                    <v-list-item link
                                                                                 @click="appendProductToEating('dinner', item.id)">
                                                                        <v-list-item-title>Add to Dinner
                                                                        </v-list-item-title>
                                                                    </v-list-item>
                                                                    <v-list-item link
                                                                                 @click="appendProductToEating('snacks', item.id)">
                                                                        <v-list-item-title>Add to Snacks
                                                                        </v-list-item-title>
                                                                    </v-list-item>
                                                                </v-list>

                                                            </v-menu>


                                                            <v-btn
                                                                color="error"
                                                                x-small
                                                                @click="deleteSelectedProduct(item.id)"
                                                            >
                                                                REMOVE
                                                            </v-btn>
                                                        </div>

                                                    </v-list-item-subtitle>

                                                </v-list-item-content>

                                                <v-list-item-action>
                                                    <v-list-item-action-text v-text="find(stores, function(e) {
                                                   return e.id === item.store_id
                                                 }).name"/>

                                                    <v-list-item-avatar
                                                        style="height: 58px; min-width: 58px; width: 58px; margin: unset; border-radius: unset;">
                                                        <v-img :src="item.image"/>
                                                    </v-list-item-avatar>
                                                </v-list-item-action>
                                            </template>
                                        </v-list-item>

                                    </template>
                                </template>
                            </v-list>
                        </v-list>
                    </div>

                </div>


            </v-flex>

        </v-layout>


        <!--        modals  starts    -->
        <!--        create dialog starts -->
        <v-dialog
            v-model="createDialog"
            persistent
            max-width="600px"
        >
            <v-card>
                <v-card-title>
                    <span class="headline"> CREATE NEW PRESET </span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-form v-model="createForm.valid">
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        label="Preset Name*"
                                        required
                                        v-model="createForm.name"
                                        :rules="createForm.nameRules"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12">
                                    <v-select
                                        :items="stores"
                                        item-value="id"
                                        item-text="name"
                                        v-model="createForm.selectedStore"
                                        :rules="createForm.selectedStoreRules"
                                        label="Store"
                                        required
                                    >

                                    </v-select>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-container>
                    <small>*indicates required field</small>

                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="red darken-1"
                        text
                        @click="createDialog = false"
                    >
                        Close
                    </v-btn>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="createPreset"
                        :disabled="!createForm.valid"
                    >
                        Create preset
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--        create dialog ends-->
        <!--        edit dialog starts-->
        <v-dialog
            v-model="editDialog"
            persistent
            max-width="600px"
        >
            <v-card>
                <v-card-title>
                    <span class="headline"> Edit preset </span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-form v-model="editForm.valid">
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        label="Preset Name*"
                                        required
                                        v-model="editForm.name"
                                        :rules="editForm.nameRules"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12">
                                    <v-select
                                        :items="stores"
                                        item-value="id"
                                        item-text="name"
                                        v-model="editForm.selectedStore"
                                        :rules="editForm.selectedStoreRules"
                                        label="Store"
                                        required
                                    >

                                    </v-select>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-container>
                    <small>*indicates required field</small>

                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="red darken-1"
                        text
                        @click="editDialog = false"
                    >
                        Close
                    </v-btn>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="editPreset"
                        :disabled="!editForm.valid"
                    >
                        Edit preset
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--        edit dialog ends-->
        <!--        store csv start  -->
        <v-dialog
            v-model="csv.modal"
            fullscreen
            hide-overlay
            transition="dialog-bottom-transition"
        >
            <v-card>
                <v-toolbar
                    dark
                    color="primary"
                >
                    <v-btn
                        icon
                        dark
                        @click="csv.modal = false"
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Store CSV</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn
                            dark
                            text
                            @click="csv.modal  = false"
                        >
                            Save
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-subheader>Columns</v-subheader>
                <p class="text-justify pl-2 pr-2">
                    Before you begin, you need to check which columns are related to a specific input. Check the first
                    row of your
                    CSV file, find the column wich represents the product name (e.g PRODUCT NAME), copy that value, and
                    paste it
                    to related input
                </p>
                <v-form ref="form" v-model="csv.valid">
                    <v-row class="xs12 pl-5 pr-5">
                        <v-col class="d-flex" cols="12" sm="6">
                            <v-text-field
                                label="Product Name Column"
                                name="product_name"
                                prepend-icon="mdi-form-textbox"
                                v-model="csv.form.product_name"
                                :rules="csv.form.product_nameRules"
                                :counter="255"
                                outlined
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col class="d-flex" cols="12" sm="6">
                            <v-text-field
                                label="Day Column"
                                name="day"
                                prepend-icon="mdi-food-fork-drink"
                                v-model="csv.form.day"
                                :rules="csv.form.dayRules"
                                :counter="255"
                                outlined
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col class="d-flex" cols="12" sm="6">
                            <v-text-field
                                label="Eating Column"
                                prepend-icon="mdi-food-fork-drink"
                                name="eating"
                                v-model="csv.form.eating"
                                :rules="csv.form.eatingRules"
                                :counter="255"
                                outlined
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col class="d-flex" cols="12" sm="6">
                            <v-file-input
                                v-model="csv.file"
                                color="deep-purple accent-4"
                                accept="text/csv"
                                label="File input"
                                placeholder="Select CSV file"
                                prepend-icon="mdi-paperclip"
                                outlined
                                :show-size="1000"
                                @change="handleFileSelect"
                                :error="csv.csvValidation.failed"
                                :error-messages="csv.csvValidation.failed ? csv.csvValidation.errorMessage : null"
                            >
                                <template v-slot:selection="{ index, text }">
                                    <v-chip
                                        v-if="index < 2"
                                        color="deep-purple accent-4"
                                        dark
                                        label
                                        small
                                    >
                                        {{ text }}
                                    </v-chip>
                                </template>
                            </v-file-input>
                        </v-col>

                        <v-col class="d-flex" cols="12" sm="12">
                            <v-btn :disabled="!storeCsvValid || !csv.valid" @click="storeCSV">
                                Store
                            </v-btn>
                        </v-col>


                    </v-row>
                </v-form>

            </v-card>
        </v-dialog>
        <!--        store csv ends -->
        <!--        modals  ends     -->


    </v-layout>
</template>

<script>
import OverlayComponent from "../../js/components/overlay/OverlayComponent";
import each from 'lodash/each'
import find from 'lodash/find'
import remove from 'lodash/remove'
import ProductGrouping from "../components/presets/ProductGrouping";

export default {
    name: "Presets",
    components: {ProductGrouping, OverlayComponent},
    data: function () {
        return {
            isBusy: false,
            presets: [],
            selected: undefined,
            preset: null,
            selectedDay: 0,
            stores: [],
            createDialog: false,
            createForm: {
                valid: false,
                selectedStore: null,
                selectedStoreRules: [
                    v => !!v || 'Need to select store',
                ],
                name: '',
                nameRules: [
                    v => !!v || 'Name is required',
                    v => v.length <= 191 || 'Name must be less than 191 characters',
                ]
            },
            editDialog: false,
            editForm: {
                valid: false,
                selectedStore: null,
                selectedStoreRules: [
                    v => !!v || 'Need to select store',
                ],
                name: '',
                nameRules: [
                    v => !!v || 'Name is required',
                    v => v.length <= 191 || 'Name must be less than 191 characters',
                ]
            },
            productAdder: {
                selectedStore: null,
                products: [],
                selectedProduct: undefined,
                search: '',
                isLoading: false,
                testModel: null,
                count: null,
                entries: []
            },
            csv: {
                file: null,
                loading: false,
                modal: false,
                form: {
                    product_name: '',
                    product_nameRules: [
                        v => !!v || 'Product Name column is required',
                        v => v.length <= 191 || 'Name must be less than 191 characters',
                    ],
                    day: '',
                    dayRules: [
                        v => !!v || 'Day column is required',
                        v => v.length <= 191 || 'Name must be less than 191 characters',
                    ],
                    eating: '',
                    eatingRules: [
                        v => !!v || 'Eating column is required',
                        v => v.length <= 191 || 'Name must be less than 191 characters',
                    ],
                },
                csvValidation: {
                    allowed: 'csv',
                    failed: false,
                    errorMessage: 'This filetype is not allowed. Please, select CSV file'
                },
                valid: false,

            }
        }
    },
    watch: {
        'productAdder.testModel': function (value, oldValue) {
            if (value === undefined || value === null) {
                this.productAdder.testModel = null;
            } else {

                let finder = find(this.productAdder.entries, function (e) {
                    return e.id === value.id
                })
                if (finder) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: 'Sorry. You cant add same product twice.',
                        color: "success",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                    this.productAdder.testModel = undefined
                } else {
                    this.productAdder.entries.unshift(value)
                    this.productAdder.testModel = undefined
                    this.productAdder.search = ''

                }
                this.productAdder.search = ''
            }
        },
        'productAdder.search': async function (val) {
            if (!val) return
            if (val.length <= 1) return
            if (this.productAdder.isLoading) return
            this.productAdder.isLoading = true

            try {
                let res = await this.$http.get('/api/preset/find', {
                    params: {
                        search: val,
                        store_id: this.productAdder.selectedStore
                    }
                });
                this.productAdder.products = res.data.products
                this.productAdder.isLoading = false


            } catch (e) {
                this.productAdder.isLoading = false
            }
        },
        selectedPreset: function (val) {
            if (val) {
                this.productAdder.selectedStore = val.store.id
                this.fetchSpecificPreset(val.id)
            } else {

            }
        }
    },
    computed: {

        storeCsvValid() {
            return !!((!this.csv.csvValidation.failed && this.csv.file) && this.selectedPreset)
        },
        selectedPreset() {
            return this.presets[this.selected]
        },
        presetData() {
            return this.preset ? this.preset['preset'][this.weekdays[this.selectedDay]] : null
        },
        weekdays() {
            return Object.keys(this.preset['preset'])
        },
        totals() {
            let rtat = {cost: 0, calories: 0}
            each(this.preset['preset'], (array, day) => {
                rtat[day] = {cost: 0, calories: 0}
                each(array, (ar, eating) => {
                    rtat[day][eating] = {cost: 0, calories: 0}
                    each(ar, (value, va) => {
                        rtat[day][eating]['cost'] += value.product.cost
                        rtat[day][eating]['calories'] += value.product.calories * value.servings

                        rtat[day]['cost'] += value.product.cost
                        rtat[day]['calories'] += (value.product.calories * value.servings)
                    })
                })
                rtat['cost'] += rtat[day]['cost']
                rtat['calories'] += rtat[day]['calories']
            });

            return rtat;
        }
    },
    mounted() {
        this.fetchPresets();
        this.fetchStores();
    },
    methods: {
        addDishToList(value) {
            this.productAdder.entries.unshift(value)

        },
        findStoreById(id) {
            let s = this.stores.find(e => {
                return e.id === id
            })
            return s.name
        },
        fetchSpecificPreset(id) {
            /**
             * with axios we have problem when big json arrives from the server
             * and axios basically freezes for 5 sec. Even when we do not decode json payload by default. SAD!
             */
            // this.isBusy = true
            // try {
            //     let res = await this.$http.get(`/api/preset/${id}`)
            //     if (res.data.success) {
            //         this.preset = res.data.data
            //     }
            //     this.isBusy = false
            //     console.log(res)
            // } catch (e) {
            //     this.isBusy = false
            // }

            this.isBusy = true
            fetch(`/api/preset/${id}`, {
                method: 'get',
                headers: new Headers({
                    'Authorization': 'Bearer ' + this.$store.state.auth.token,
                }),
            })
                .then(res => {
                    if (!res.ok) {
                        throw res
                    }
                    return res.json();
                })
                .then(res => {
                    this.preset = res.data
                })
                .catch(e => {

                }).finally(() => {
                this.isBusy = false
            })
        },

        appendSearchInput(val) {
            if (val) {
                this.productAdder.search = val.trim()
            }
        },
        async fetchStores() {
            try {
                let res = await this.$http.get('/api/store/defaults')

                this.stores = res.data.data;
            } catch (e) {

            }

        },
        async fetchPresets() {
            this.isBusy = true
            try {
                let res = await this.$http.get(`/api/preset`);
                this.presets = res.data.data
                this.isBusy = false
            } catch (e) {
                this.isBusy = false
            }
        },
        async createPreset() {
            try {
                let res = await this.$http.post(`/api/preset`, {
                    name: this.createForm.name,
                    store_id: this.createForm.selectedStore,
                    preset_case: 1,
                })

                if (res.data.success) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: res.data.message,
                        color: "success",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                    await this.fetchPresets()
                    this.createDialog = false
                    // this.createForm = null;
                }

            } catch (e) {
                if (e.response.data.errors) {
                    Object.keys(e.response.data.errors).forEach(i => {
                        e.response.data.errors[i].forEach(z => {
                            this.$store.commit('snackbar/pushMessage', {
                                message: z,
                                color: "error",
                                timeout: 5000,
                                right: true,
                                bottom: true
                            })
                        })

                    })
                } else if (e.response.data.message) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: e.response.data.message,
                        color: "error",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                }
            }
        },
        async editPreset() {
            try {
                let res = await this.$http.patch(`/api/preset/${this.selectedPreset.id}`, {
                    name: this.editForm.name,
                    store_id: this.editForm.selectedStore,
                    preset_case: 1,
                })

                if (res.data.success) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: res.data.message,
                        color: "success",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                    await this.fetchPresets()
                    this.editDialog = false
                }

            } catch (e) {
                if (e.response.data.errors) {
                    Object.keys(e.response.data.errors).forEach(i => {
                        e.response.data.errors[i].forEach(z => {
                            this.$store.commit('snackbar/pushMessage', {
                                message: z,
                                color: "error",
                                timeout: 5000,
                                right: true,
                                bottom: true
                            })
                        })

                    })
                } else if (e.response.data.message) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: e.response.data.message,
                        color: "error",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                }
            }
        },
        async deletePreset() {
            if (confirm('Are you you want to delete this preset?')) {
                try {
                    let res = await this.$http.delete(`/api/preset/${this.selectedPreset.id}`)
                    if (res.data.success) {
                        this.$store.commit('snackbar/pushMessage', {
                            message: res.data.message,
                            color: "success",
                            timeout: 5000,
                            right: true,
                            bottom: true
                        })
                        await this.fetchPresets()
                        this.selected = null
                    }
                } catch (e) {
                    if (e.response.data.errors) {
                        Object.keys(e.response.data.errors).forEach(i => {
                            e.response.data.errors[i].forEach(z => {
                                this.$store.commit('snackbar/pushMessage', {
                                    message: z,
                                    color: "error",
                                    timeout: 5000,
                                    right: true,
                                    bottom: true
                                })
                            })

                        })
                    } else if (e.response.data.message) {
                        this.$store.commit('snackbar/pushMessage', {
                            message: e.response.data.message,
                            color: "error",
                            timeout: 5000,
                            right: true,
                            bottom: true
                        })
                    }
                }
            }
        },
        async publishPreset() {

            try {
                let res = await this.$http.post(`/api/preset/publish/${this.selectedPreset.id}`)

                if (res.data.success) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: res.data.message,
                        color: "success",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                    await this.fetchPresets()
                }

            } catch (e) {
                if (e.response.data.errors) {
                    Object.keys(e.response.data.errors).forEach(i => {
                        e.response.data.errors[i].forEach(z => {
                            this.$store.commit('snackbar/pushMessage', {
                                message: z,
                                color: "error",
                                timeout: 5000,
                                right: true,
                                bottom: true
                            })
                        })

                    })
                } else if (e.response.data.message) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: e.response.data.message,
                        color: "error",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                }
            }
        },
        async increaseQuantity(id, eating, day, servings) {
            this.isBusy = true
            try {
                let res = await this.$http.patch(`/api/preset/${this.selectedPreset.id}/servings`, {
                    product_id: id,
                    eating: eating,
                    servings: servings + 1,
                    day: this.weekdays[day]
                })
                if (res.data.success) {
                    await this.fetchPresets();
                    this.$store.commit('snackbar/pushMessage', {
                        message: res.data.message,
                        color: "success",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                }
                this.isBusy = false
            } catch (e) {
                this.isBusy = false
                if (e.response.data.errors) {
                    Object.keys(e.response.data.errors).forEach(i => {
                        e.response.data.errors[i].forEach(z => {
                            this.$store.commit('snackbar/pushMessage', {
                                message: z,
                                color: "error",
                                timeout: 5000,
                                right: true,
                                bottom: true
                            })
                        })

                    })
                } else if (e.response.data.message) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: e.response.data.message,
                        color: "error",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                }
            }
        },
        async decreaseQuantity(id, eating, day, servings) {
            this.isBusy = true
            try {
                let res = await this.$http.patch(`/api/preset/${this.selectedPreset.id}/servings`, {
                    product_id: id,
                    eating: eating,
                    servings: servings - 1,
                    day: this.weekdays[day]
                })
                if (res.data.success) {
                    await this.fetchPresets();
                    this.$store.commit('snackbar/pushMessage', {
                        message: res.data.message,
                        color: "success",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                }
                this.isBusy = false
            } catch (e) {
                this.isBusy = false
                if (e.response.data.errors) {
                    Object.keys(e.response.data.errors).forEach(i => {
                        e.response.data.errors[i].forEach(z => {
                            this.$store.commit('snackbar/pushMessage', {
                                message: z,
                                color: "error",
                                timeout: 5000,
                                right: true,
                                bottom: true
                            })
                        })

                    })
                } else if (e.response.data.message) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: e.response.data.message,
                        color: "error",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                }

            }
        },
        async selectDay(key) {
            this.selectedDay = key
        },
        async appendProductToEating(eating, product_id) {
            try {
                let res = await this.$http.post(`/api/preset/${this.selectedPreset.id}`, {
                    product_id: product_id,
                    eating: eating,
                    day: this.weekdays[this.selectedDay]
                })
                if (res.data.success) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: res.data.message,
                        color: "success",
                        timeout: 5000,
                        left: true,
                        bottom: true
                    })
                    await this.fetchPresets()
                }
            } catch (e) {
                if (e.response.data.errors) {
                    Object.keys(e.response.data.errors).forEach(i => {
                        e.response.data.errors[i].forEach(z => {
                            this.$store.commit('snackbar/pushMessage', {
                                message: z,
                                color: "error",
                                timeout: 5000,
                                right: true,
                                bottom: true
                            })
                        })

                    })
                } else if (e.response.data.message) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: e.response.data.message,
                        color: "error",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                }
            }
        },
        deleteSelectedProduct(product_id) {
            if(confirm('Delete product from pre-select?')) {
                let originalEntriesLength = this.productAdder.entries.length
                this.productAdder.entries = remove(this.productAdder.entries, function (e, i) {
                    return e.id !== product_id
                })

                if (originalEntriesLength !== this.productAdder.entries.length) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: 'Product was deleted from pre-select list',
                        color: "success",
                        timeout: 5000,
                        left: true,
                        bottom: true
                    })
                }
            }
        },
        async deleteProductFromPreset(id, eating, day) {
            if(confirm('Are you sure you want to delete this product from the preset?')) {
                try {
                    let res = await this.$http.put(`/api/preset/${this.selectedPreset.id}`, {
                        product_id: id,
                        eating: eating,
                        day: day,
                    })

                    if (res.data.success) {
                        this.$store.commit('snackbar/pushMessage', {
                            message: res.data.message,
                            color: "success",
                            timeout: 5000,
                            right: true,
                            bottom: true
                        })
                        await this.fetchPresets()

                    }
                } catch (e) {
                    if (e.response.data.errors) {
                        Object.keys(e.response.data.errors).forEach(i => {
                            e.response.data.errors[i].forEach(z => {
                                this.$store.commit('snackbar/pushMessage', {
                                    message: z,
                                    color: "error",
                                    timeout: 5000,
                                    right: true,
                                    bottom: true
                                })
                            })

                        })
                    } else if (e.response.data.message) {
                        this.$store.commit('snackbar/pushMessage', {
                            message: e.response.data.message,
                            color: "error",
                            timeout: 5000,
                            right: true,
                            bottom: true
                        })
                    }
                }
            }
        },
        openEditDialog() {
            this.editForm.selectedStore = this.selectedPreset.store.id
            this.editForm.name = this.selectedPreset.name
            this.editDialog = true
        },
        async storeCSV() {
            this.csv.loading = true
            let data = new FormData()
            data.set('csv', this.csv.file)
            data.set('product_name', this.csv.form.product_name)
            data.set('day', this.csv.form.day)
            data.set('eating', this.csv.form.eating)
            try {
                let res = await this.$http.post(`/api/preset/${this.selectedPreset.id}/csv`, data,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })

                if (res.data.success) {
                    this.csv.loading = false
                    this.$store.commit('snackbar/pushMessage', {
                        message: res.data.message,
                        color: "success",
                        timeout: 5000,
                        left: true,
                        bottom: true
                    })

                    // this.csv.form.day = ''
                    // this.csv.form.eating = ''
                    // this.csv.form.product_name = ''
                    // this.csv.file = null

                    await this.fetchPresets()


                    if (res.data.errors) {
                        Object.keys(res.data.errors).forEach(i => {
                            Object.keys(res.data.errors[i]).forEach(z => {
                                this.$store.commit('snackbar/pushMessage', {
                                    message: z + ' - ' + res.data.errors[i][z],
                                    color: "error",
                                    timeout: 10000,
                                    right: true,
                                    bottom: true
                                })
                            })
                        })
                    }

                }
            } catch (e) {
                this.csv.loading = false
                if (e.response.data.errors) {
                    Object.keys(e.response.data.errors).forEach(i => {
                        e.response.data.errors[i].forEach(z => {
                            this.$store.commit('snackbar/pushMessage', {
                                message: z,
                                color: "error",
                                timeout: 5000,
                                right: true,
                                bottom: true
                            })
                        })

                    })
                } else if (e.response.data.message) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: e.response.data.message,
                        color: "error",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                }


            }
        },
        handleFileSelect(event) {
            let fileExt = this.checkFileExtensions(this.csv.file.name);
            if (fileExt[0] !== this.csv.csvValidation.allowed) {
                this.csv.csvValidation.failed = true;
                this.csv.file = undefined;
            } else {
                this.csv.csvValidation.failed = false;
            }
        },
        checkFileExtensions(filename) {
            return (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename) : undefined;
        },
        find: find
    }
}
</script>

<style scoped lang="scss">

.delete-button--absolute {
    position: absolute;
    right: -8px;
    top: -5px;
}

.v-expansion-panel {
    background: unset;

    &:before {
        box-shadow: unset;
    }
}

.sticky-store-products {
    position: sticky;
    top: 60px;
    z-index: 2;
}

.sticky-head-total {
    position: sticky;
    top: 60px;
    padding: 30px 0 15px 0;
    background: #ffffff;
    z-index: 2;
}

.sticky-head-day {
    position: sticky;
    top: 125px;
    padding: 5px 0 15px 0;
    background: #ffffff;
    z-index: 2;
}

.sticky-small-stats {
    position: sticky;
    top: 165px;
    padding: 0 8px 15px 8px;
    background: #ffffff;
    z-index: 1;
}


.fooz {
    display: flex;
    margin-bottom: 14px;
    width: 100%;

    .foos {
        width: calc(100% / 5);

        &.foos-4 {
            width: calc(100% / 4);
        }

        display: flex;
        flex-direction: column;
        padding: 0 10px;
        text-align: center;
        border-right: 1px solid hsl(0 0% 0% / 0.1);

        &:last-child {
            border-right: unset;
        }

        .title {
            font-size: 12px !important;
            font-weight: 500;
            margin-bottom: 6px;
            line-height: 1;
        }

        .text {
            font-size: 14px !important;
            display: flex;
            justify-content: center;
        }
    }
}

.v-speed-dial {
    position: fixed;
    z-index: 5;
}

.v-btn--floating {
    position: relative;
}

.food-block__group-img {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    width: 58px;
    min-width: 58px;
    height: 58px;
    margin-right: 18px;
    border-radius: 8px;
}

.food-block__group-img img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
    width: calc(50% - 2px);
    height: calc(50% - 2px);
    border-radius: 4px;

    &:nth-child(even) {
        margin-left: 4px;
    }

    &:nth-child(1), &:nth-child(2) {
        margin-bottom: 4px;
    }
}


.food-block__show--colums {
    flex-direction: column;
}

.food-block__show {
    flex-wrap: wrap;
    display: flex;
    padding-top: 14px;
    border-top: 1px solid lightgrey;
}

.food-group__wrap {
    position: relative;
    width: 100%;

    button.food-block__delete {
        position: absolute;
        top: 0;
        right: 0;
        margin-top: -3px;
        margin-right: 0;
    }

    &:last-child {
        .food-group__block {
            padding-bottom: 0;
            border-bottom: unset;
        }
    }
}


.food-group__block {
    display: flex;
    width: 100%;
    align-items: center;
    margin-bottom: 14px;

    &:last-child {
        margin-bottom: 0;
    }

    img {
        width: 30px;
        max-width: 30px;
        height: 30px;
        object-fit: cover;
        border-radius: 4px;
        margin-right: 14px;
    }

    span.name {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        position: relative;
        max-width: calc(100% - 22px);
    }

    span.text {
        max-width: 116px;
        width: 100%;

        font-weight: 500;
        padding-right: 6px;

        &:last-child {
            max-width: 70px;
        }
    }
}

.food-override-style {
    padding: 6px;
    font-size: small;
}

.item-bordered {
    box-shadow: 0 3px 1px -2px rgba(0, 0, 0, .2), 0 2px 2px 0 rgba(0, 0, 0, .14), 0 1px 5px 0 rgba(0, 0, 0, .12);
    border-radius: 6px;
    margin-bottom: 5px;

    &:last-child {
        margin-bottom: unset;
    }
}


.food-group__block-group {
    display: flex;
    max-width: 300px;
    width: 100%;
    line-height: 1;
    padding-right: 8px;
    &--trunc {
        max-width: 126px;
    }
    @media screen and (max-width: 650px) {
        max-width: 160px;

    }

        .food__links {
        margin-top: -1px;
    }
}


</style>
