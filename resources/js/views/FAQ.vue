<template>
    <app-layout>
        <div class="wrapper wrapper__padded wrapper__break">
            <h1 class="section__caption__title">
                FAQ
            </h1>
            <h2>
                <strong>Why should I use The Hot Meal?</strong>
            </h2>
            <p>
                The Hot Meal is a delicious way to eat Trader Joe's foods—that are healthy and affordable and easy to
                make—all while losing weight. We have expert knowledge on the foods they carry and we use their
                selection to craft an effective weight loss program.
                If you would like to be more organized in your eating, rather than eating on the fly and
                increasing the chances of your weight gain, The Hot Meal is an excellent tool.
            </p>
            <h2>
                <strong>
                    How does the site work?
                </strong>
            </h2>

            <p>
                You register, you set your calorie goal, we craft weekly meal plans that make it easy for
                you to eat within your calorie goal, then you shop for your meal items at Trader Joe’s.
            </p>

            <h2>
                <strong>
                    How long do your subscriptions last?
                </strong>
            </h2>

            <p>
                We have one-month, three-month, six-month, nine-month, and 12-month subscriptions.
            </p>


            <h2>
                <strong>
                    Does your calorie-counting approach really work?
                </strong>
            </h2>

            <p>
                It sure does, for many people. The reason is that, if you actually eat within the framework of your
                body's needed calories, as opposed to consuming a surplus beyond your TDEE (Total Daily Energy
                Expenditure, which is a measure of how many calories you burn per day), your body will lose calories.
                If your body is able to eat 3500 calories below your TDEE in one week, you will lose one pound, because
                3500 calories is equal to one pound. However, your body still needs fuel, so we do not recommend a
                weight loss of more than 2 pounds a week. Also, we do not replace medical advice and we do not accept
                any liability for your medical profile or history: please speak to your doctor before undergoing any
                nutritional and medical changes on this site or elsewhere.
            </p>

            <h2>
                <strong>
                    Does your calorie-counting approach really work?
                </strong>
            </h2>

            <p>
                It sure does, for many people. The reason is that, if you actually eat within the framework of your
                body's needed calories, as opposed to consuming a surplus beyond your TDEE (Total Daily Energy
                Expenditure, which is a measure of how many calories you burn per day), your body will lose calories.
                If your body is able to eat 3500 calories below your TDEE in one week, you will lose one pound, because
                3500 calories is equal to one pound. However, your body still needs fuel, so we do not recommend a
                weight loss of more than 2 pounds a week.
                <strong>
                    Also, we do not replace medical advice and we do not accept
                    any liability for your medical profile or history: please speak to your doctor before undergoing any
                    nutritional and medical changes on this site or elsewhere.
                </strong>
            </p>

            <h2>
                <strong>
                    What tips can help ensure that I lose weight with your meal plans?
                </strong>
            </h2>

            <p>
                With our meals, the biggest tip is to follow it closely! Do not add more calories to the plan. The foods
                we picked are so delicious and, if you eat the right servings, you will be more successful in your
                weight management. Do not eat much less than you need either. Health guidelines warn that women should
                not consume less than 1,000 calories a day and men should not consume less than 1,200.
            </p>


            <h2>
                <strong>
                    Can I use the site even if I don't want to lose or gain weight?
                </strong>
            </h2>

            <p>
                Absolutely! If you find that you run helter-skelter or eat poorly because your meals aren't planned,
                this site will help you plan your meals. And rather than having to go to 5 different grocery stores and
                cook 11 different recipes to eat, you'll be able to shop <strong><i>one</i></strong>  store and
                <strong><i>streamline</i></strong> your eating habits
                for the healthier and for the better.
            </p>

            <h2>
                <strong>
                    Who picks the meals on your site?
                </strong>
            </h2>

            <p>
                We have food editors, professional chefs, and regular Trader Joe's shoppers who select foods that make a
                good health grade. We try our hardest not to include any foods on the list that we think wouldn't be
                beneficial to weight management.
            </p>

            <h2>
                <strong>
                    Are you affiliated with Trader Joe's?
                </strong>
            </h2>

            <p>
                We are not affiliated with Trader Joe's—we design effective meal plans and a meal style that uses Trader Joe's foods.
            </p>

            <h2>
                <strong>
                    Will I have to spend a lot of money to get my meals?
                </strong>
            </h2>

            <p>
                Not at all. Trader Joe's items are not as expensive as their counterparts', which is why we use their
                selections to craft effective meal plans. If you can enjoy delectable food that's simple and
                straightforward, you can cover your meals from around $50 and upwards, for a week.
            </p>

            <h2>
                <strong>
                    What if I don't like the selection of meals your site has chosen for me?
                </strong>
            </h2>

            <p>
                There's a regenerate button in the Meals section, which will allow you to regenerate any set of meals
                you don't wish to use. But, please keep in mind, regenerating your meal will also regenerate your
                weekly grocery list.
            </p>

            <h2>
                <strong>
                    How do you determine how many calories I need to eat for weight management?
                </strong>
            </h2>

            <p>
                We ask for your gender, age, weight, height, and weight loss/management goal, and we use TDEE
                calculations for a sedentary lifestyle (which most people have). If you are more active than
                sedentary, you can always manually input the exercise you've completed for each day.
            </p>

            <h2>
                <strong>
                    Whom can I contact for more help or support?
                </strong>
            </h2>

            <p>
                ou can contact our friendly support staff at
                <a href="mailto:hello@thehotmeal.co.uk">hello@thehotmeal.co.uk</a>,
                or you can speak to your account coach directly, for free.
            </p>

        </div>
    </app-layout>
</template>

<script>
    export default {
        name: "FAQ"
    }
</script>

<style scoped lang="scss">
    .wrapper {
        width: 100%;
    }
    .section__caption__title {
        margin: 20px 0;
    }
    h2 {
        margin: 30px 0 14px 0;
        font-size: 20px;
    }
    p {
        font-weight: 500;
        line-height: 1.5;
        margin-bottom: 14px;
        &:last-child {
            margin-bottom: 0;
        }
    }
    ul.list-disc {
        display: flex;
        flex-direction: column;
        font-weight: 500;
        line-height: 1.5;
        margin: 20px 0;
        li {
            position: relative;
            margin-bottom: 10px;
            padding-left: 16px;
            &:last-child {
                margin-bottom: 0;
            }
            &:after {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 6px;
                height: 6px;
                background: #000;
                border-radius: 50%;
                margin-top: 8px;
            }
        }
    }
    a {
        color: var(--purple);
    }
</style>
