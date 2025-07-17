<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <style>
        /* Star Rating Styles */
        #react-root-* {
            display: flex !important;
            gap: 0.5rem;
            font-size: 1.5rem;
        }

        #react-root-* span {
            cursor: pointer;
            transition: transform 0.2s;
            user-select: none;
        }

        #react-root-* span:hover {
            transform: scale(1.2);
        }
    </style>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => 'My Company',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => 'Home', 'url' => ['/site/index']],
                ['label' => 'About', 'url' => ['/site/about']],
                ['label' => 'Contact', 'url' => ['/site/contact']],
                Yii::$app->user->isGuest ? (
                    ['label' => 'Login', 'url' => ['/site/login']]
                ) : (
                    '<li>'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'
                )
            ],
        ]);
        NavBar::end();
        ?>

        <div class="container pt-10">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>
    <script src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const islands = document.querySelectorAll('astro-island[component-url*="StarRating"]');

            islands.forEach(island => {
                // 1. Remove the fallback content to prevent duplicates
                const fallback = island.querySelector('[slot="fallback"]');
                if (fallback) fallback.remove();

                // 2. Create a single container for React
                const container = document.createElement('div');
                island.appendChild(container);

                // 3. Load and render the component
                const hydrate = async () => {
                    try {
                        const {
                            default: StarRating
                        } = await import(island.getAttribute('component-url'));
                        const props = JSON.parse(island.getAttribute('props'));

                        ReactDOM.createRoot(container).render(
                            React.createElement(StarRating, {
                                ...props,
                                // Force re-render with proper context
                                key: Math.random().toString(36).substring(2)
                            })
                        );

                        console.log('StarRating successfully rendered');
                    } catch (error) {
                        console.error('Rendering failed:', error);
                    }
                };

                // 4. Disable Astro's automatic hydration
                island.setAttribute('client', 'none');

                // 5. Run our manual hydration
                setTimeout(hydrate, 0);
            });
        });
    </script>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>