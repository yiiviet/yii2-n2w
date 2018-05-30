<?php
/**
 * @link https://github.com/yiiviet/yii2-n2w
 * @copyright Copyright (c) 2018 Yii Viet
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yiiviet\tests\unit\n2w;

use Yii;

use PHPUnit\Framework\TestCase;

/**
 * Lớp {Test}
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
class Test extends TestCase
{

    /**
     * @dataProvider dataProvider
     */
    public function testFormat($num, $text)
    {
        $component = Yii::createObject([
            'class' => MyComponentTest::class,
            'number' => $num,
            'notNumber' => $text
        ]);

        $this->assertEquals($text, $component->numberFormat);
        $this->assertFalse($component->notNumberFormat);
    }


    public function dataProvider()
    {
        return [
            [10, 'Mười'],
            [-20, 'Âm hai mươi'],
            [12, 'Mười hai'],
            [21, 'Hai mươi một'],
            [100, 'Một trăm'],
            [1000, 'Một ngàn'],
            [10000, 'Mười ngàn'],
            [100021, 'Một trăm ngàn hai mươi một'],
            [24568200, 'Hai mươi bốn triệu năm trăm sáu mươi tám ngàn hai trăm'],
            [240568200, 'Hai trăm bốn mươi triệu năm trăm sáu mươi tám ngàn hai trăm']
        ];
    }


}
