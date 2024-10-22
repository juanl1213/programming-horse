<div class="bg-white dark:bg-gray-800 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 dark:text-white box">
                <p class="text">{{ $slot }}</p>
</div>

<style>
    .text{
        font-size: 20px; padding-top: 13px;
    }
    .box {
        margin-top: 50px; font-family:'Urbanist'; padding-bottom: 20px; width: 165px; text-align: center; border-radius: 10px;
    }
    @media (max-width: 600px) {
            .text {
                font-size: 12px;
            }
            .box {
                width: 95px;
                margin-top: 25px;
                padding-bottom: 15px;
            }
            .rules-box{
                width: 330px;
                font-size: 10px;
            }


        }
</style>