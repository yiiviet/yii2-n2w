<?php
/**
 * @link https://github.com/yiiviet/yii2-n2w
 * @copyright Copyright (c) 2018 Yii Viet
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yiiviet\tests\unit\n2w;

use yii\base\Component;

use yiiviet\n2w\Behavior as N2WBehavior;

/**
 * Lớp {MyComponentTest}
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
class MyComponentTest extends Component
{
    /**
     * @var int
     */
    public $number;

    /**
     * @var mixed
     */
    public $notNumber;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => N2WBehavior::class,
                'properties' => ['number', 'notNumber']
            ]
        ];
    }


}
