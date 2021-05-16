<template>
    <section class="featured-in-section">
        <div class="section-header">
            <span>Featured in</span>
        </div>
        <div class="section-content">
            <img src="/assets/images/featured-in-spotify.png" alt="" class="featured-in-spotify">
            <img src="/assets/images/featured-in-newsy.png" alt="" class="featured-in-newsy">
            <img src="/assets/images/featured-in-anchor.png" alt="" class="featured-in-anchor">
        </div>
    </section>
</template>

<script>
export default {
    name: "SectionFeaturedIn",
}
</script>

<style scoped lang="scss">
    .featured-in-section {
        padding: 62px 25px 28px;
        position: relative;
        text-align: center;

        .section-header {
            font-family: Gilroy;
            font-style: normal;
            font-weight: 300;
            font-size: 30px;
            line-height: 138.18%;
            text-align: center;
            color: #292D34;
        }
        .section-content {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-flow: row nowrap;

            img {
                height: 51px;
                margin: 0 41px;

                &.featured-in-spotify {
                    padding-left: 10px;
                }
                &.featured-in-newsy {
                    margin-top: 15px;
                }
                &:last-child {
                    height: auto;
                    width: 174px;
                }
            }

            @media (max-width: 768px) {
                img {
                width: 33%;
                height: auto;
                object-fit: contain;
                margin: 0;

                    &.featured-in-spotify {
                        padding-left: 0;
                    }
                    &.featured-in-newsy {
                        padding: 8px 5% 0;
                        margin: 0;
                        transform: translateY(10%);
                    }
                     &:last-child {
                        width: 33%;
                    }
                }
            }
            @media (max-width: 400px) {
                img {
                    &.featured-in-newsy {
                        transform: translateY(5%);
                    }
                }
            }
        }
    }
</style>
