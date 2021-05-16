<?php

namespace App\Console\Commands;

use App\Content;
use App\Quote;
use App\Setting;
use App\SiteContent;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the "Hot Meal Planner"';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info("Creating admin...");
        $admin = User::make([
            'name' => 'Admin',
            'last_name' => 'Admin',
            'weight' => 60,
            'height' => 170,
            'gender' => 'female',
            'phone' => '12345678901',
            'email' => env("ADMIN_EMAIL"),
            'is_spam_wanted' => true,
            'age' => 21,
            'password' => Hash::make('password'),
        ]);
        $admin->is_admin = true;
        $admin->finished_setup = true;
        $admin->activated_at = now();
        $admin->save();

        $this->info("Admin created successfully!");

        $this->call('set_stripe');

        $this->info("Creating site content...");


        Setting::create([
            'name' => Setting::SOCIALLINKS,
            'value' => 'empty',
            'settings_json' => json_encode([]),
        ]);

        SiteContent::create([
            'block_name' => 'Home',
            'title'      => 'Try our easy meal & grocery planner for a healthier lifestyle!',
            'description'=> 'Simply drag & drop foods from any store to plan your weekly meals'
        ]);
        SiteContent::create([
            'block_name' => 'How it works',
            'title'      => 'Plan all your weekly groceries & calories in 5 minutes or less!',
            'description'=> '1. Drag & drop foods to eat weekly from any grocery store
                            2. Set a calorie limit to manage your foods & your weight
                            3. Follow the meal plan generated & achieve healthy goals!'
        ]);
        SiteContent::create([
            'block_name' => 'Why it works',
            'title'      => 'TheHotMeal.co.uk helps to reverse weight gain by helping you eat just the amount of calories you need.',
            'description'=> 'Most of us go to the grocery store & shop for whatever will feed us. With our meal planner,
             you can now shop grocery stores right on our platform, plan what to eat before you go, and track your
             calorie intake. If you\'re looking to lose weight, the result will be weight loss, because you\'re planning
              ahead, according to your Daily Calorie Goal. Since everyone grocery shops, why not do it with your weight
               goals in mind? It\'s both effective and practical.'
        ]);

        $this->defaultPages();

        Quote::create([
            'quote' => "You're a champ, no matter what. Good job & keep going!"
        ]);
        $this->info("Site content created successfully!");
    }

    private function defaultPages()
    {
        $pages = [
            [
                'slug' => 'about',
                'title' => 'About',
                'body' => '<p>about us text</p>',
            ],
            [
                'slug' => 'faq',
                'title' => 'FAQ',
                'body' => "<h4>Why should I use The Hot Meal?</h4>
<p>The Hot Meal is a delicious way to eat Trader Joe's foods—that are healthy and affordable and easy to make—all while losing weight. We have expert knowledge on the foods they carry and we use their selection to craft an effective weight loss program. If you would like to be more organized in your eating, rather than eating on the fly and increasing the chances of your weight gain, The Hot Meal is an excellent tool.
</p>
<h4>How does the site work?</h4>
<p>You register, you set your calorie goal, we craft weekly meal plans that make it easy for you to eat within your calorie goal, then you shop for your meal items at Trader Joe’s.
</p>
<h4>How long do your subscriptions last?</h4>
<p>We have one-month, three-month, six-month, nine-month, and 12-month subscriptions.
</p>
<h4>Does your calorie-counting approach really work?</h4>
<p>It sure does, for many people. The reason is that, if you actually eat within the framework of your body's needed calories, as opposed to consuming a surplus beyond your TDEE (Total Daily Energy Expenditure, which is a measure of how many calories you burn per day), your body will lose calories. If your body is able to eat 3500 calories below your TDEE in one week, you will lose one pound, because 3500 calories is equal to one pound. However, your body still needs fuel, so we do not recommend a weight loss of more than 2 pounds a week. Also, we do not replace medical advice and we do not accept any liability for your medical profile or history: please speak to your doctor before undergoing any nutritional and medical changes on this site or elsewhere.
</p>
<h4>What tips can help ensure that I lose weight with your meal plans?</h4>
<p>With our meals, the biggest tip is to follow it closely! Do not add more calories to the plan. The foods we picked are so delicious and, if you eat the right servings, you will be more successful in your weight management. Do not eat much less than you need either. Health guidelines warn that women should not consume less than 1,000 calories a day and men should not consume less than 1,200.
</p>
<h4>Can I use the site even if I don't want to lose or gain weight?</h4>
<p>Absolutely! If you find that you run helter-skelter or eat poorly because your meals aren't planned, this site will help you plan your meals. And rather than having to go to 5 different grocery stores and cook 11 different recipes to eat, you'll be able to shop one store and streamline your eating habits for the healthier and for the better.
</p>
<h4>Who picks the meals on your site?</h4>
<p>We have food editors, professional chefs, and regular Trader Joe's shoppers who select foods that make a good health grade. We try our hardest not to include any foods on the list that we think wouldn't be beneficial to weight management.
</p>
<h4>Are you affiliated with Trader Joe's?</h4>
<p>We are not affiliated with Trader Joe's—we design effective meal plans and a meal style that uses Trader Joe's foods.
</p>
<h4>Will I have to spend a lot of money to get my meals?</h4>
<p>Not at all. Trader Joe's items are not as expensive as their counterparts', which is why we use their selections to craft effective meal plans. If you can enjoy delectable food that's simple and straightforward, you can cover your meals from around $50 and upwards, for a week.
</p>
<h4>What if I don't like the selection of meals your site has chosen for me?</h4>
<p>There's a regenerate button in the Meals section, which will allow you to regenerate any set of meals you don't wish to use. But, please keep in mind, regenerating your meal will also regenerate your weekly grocery list.
</p>
<h4>How do you determine how many calories I need to eat for weight management?</h4>
<p>We ask for your gender, age, weight, height, and weight loss/management goal, and we use TDEE calculations for a sedentary lifestyle (which most people have). If you are more active than sedentary, you can always manually input the exercise you've completed for each day.
</p>
<h4>Whom can I contact for more help or support?</h4>
<p>You can contact our friendly support staff at hello@thehotmeal.co.uk, or you can speak to your account coach directly, for free.</p>",
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact',
                'body' => '<p>
For corporate inquiries, please send an initial message to hello@thehotmeal.co.uk, and your email will be routed from there.
</p>
<p>For customer support, please contact hello@thehotmeal.co.uk, and include your account information and query.
</p>
<p>For personalized support, please log in and check for your Site Advisor’s name at the top of your dashboard.
</p>
<p>Email support is available Monday through Friday, 9am to 6pm. We do our best to issue an immediate response and facilitate speedy processes for any specific issue to resolve.
</p>
<p>71-75 Shelton St. West End, London WC2H 9JQ
</p>',
            ],
            [
                'slug' => 'advertising',
                'title' => 'Advertising',
                'body' => '<p>advertising text</p>',
            ],
            [
                'slug' => 'disclaimer',
                'title' => 'Disclaimer',
                'body' => '<p>disclaimer text</p>',
            ],
            [
                'slug' => 'privacy',
                'title' => 'Privacy',
                'body' => '<p>privacy text</p>',
            ],
        ];
        $order = 10;

        foreach ($pages as $page) {
            $content = new Content;
            $content->slug = $page['slug'];
            $content->title = $page['title'];
            $content->body = $page['body'];
            $content->type = 'page';
            $content->og_title = 'og title';
            $content->description = 'description text';
            $content->og_description = 'og description text';
            $content->sitemaps_weight = 0.1;
            $content->order = $order;
            $content->save();
            $order += 10;
        }
    }
}
