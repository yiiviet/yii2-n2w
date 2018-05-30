<?php
/**
 * @link https://github.com/yiiviet/yii2-payment
 * @copyright Copyright (c) 2017 Yii Viet
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yiiviet\n2w;

use yii\base\Behavior as BaseBehavior;
use yii\base\InvalidConfigException;

/**
 * Lớp Behavior hổ trợ chuyển đổi giá trị các thuộc tính là số sang chữ số đối với các lớp thuộc `Component`.
 *
 * Ví dụ:
 *
 * ```php
 *
 * class MyComponent extends Component {
 *
 *      public $money = '100';
 *
 *      public function behaviors() {
 *          return [
 *              'class' => 'yiiviet\n2w\Behavior',
 *              'property' => 'money',
 *              'unit' => 'đồng'
 *          ];
 *      }
 *
 * }
 *
 * $component = new MyComponent;
 * $component->moneyFormat; // Một trăm đồng
 *
 * ```
 *
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
class Behavior extends BaseBehavior
{

    const DICTIONARY = [
        0 => 'không',
        1 => 'một',
        2 => 'hai',
        3 => 'ba',
        4 => 'bốn',
        5 => 'năm',
        6 => 'sáu',
        7 => 'bảy',
        8 => 'tám',
        9 => 'chín',
        10 => 'mười',
        11 => 'mười một',
        12 => 'mười hai',
        13 => 'mười ba',
        14 => 'mười bốn',
        15 => 'mười năm',
        16 => 'mười sáu',
        17 => 'mười bảy',
        18 => 'mười tám',
        19 => 'mười chín',
        20 => 'hai mươi',
        30 => 'ba mươi',
        40 => 'bốn mươi',
        50 => 'năm mươi',
        60 => 'sáu mươi',
        70 => 'bảy mươi',
        80 => 'tám mươi',
        90 => 'chín mươi',
        100 => 'trăm',
        1000 => 'ngàn',
        1000000 => 'triệu',
        1000000000 => 'tỷ',
        1000000000000 => 'nghìn tỷ',
        1000000000000000 => 'ngàn triệu triệu',
        1000000000000000000 => 'tỷ tỷ'
    ];

    const SEPARATOR = ' ';

    const HYPHEN = ' ';

    const CONJUNCTION = ' ';

    const NEGATIVE = 'Âm ';

    const DECIMAL = ' phẩy ';

    /**
     * @var string đơn vị sau khi chuyển đổi số sang chữ số.
     * Ví dụ: đồng, cái, kg, tấn, tạ, yến...
     */
    public $unit = '';

    /**
     * @var string Suffix dùng để tạo ra property đã chuyển đối số sang chữ số.
     * Ví dụ property là `money` để show dưới dạng chữ số sẽ là `moneyFormat`.
     */
    public $suffix = 'Format';

    /**
     * @var string Thuộc tính cần hổ trợ chuyển giá trị số thành chữ số.
     */
    public $property;

    /**
     * @var array Danh sách thuộc tính cần hổ trợ chuyển giá trị số thành chữ số.
     */
    public $properties = [];

    /**
     * @var array Danh sách thuộc tính `magic getter` map với thuộc tính của `owner` cần hổ trợ chuyển đổi.
     */
    protected $propertiesFormatted = [];

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->properties = array_merge((array)$this->property, $this->properties);

        if (empty($this->properties)) {
            throw new InvalidConfigException('Property need to format must be set!');
        } else {
            foreach ($this->properties as $property) {
                $this->propertiesFormatted[$property . $this->suffix] = $property;
            }
        }

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        return parent::canGetProperty($name, $checkVars) || array_key_exists($name, $this->propertiesFormatted);
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function __get($name)
    {
        if (isset($this->propertiesFormatted[$name])) {
            $property = $this->propertiesFormatted[$name];
            if ($result = $this->n2w($this->owner->{$property})) {
                return $result . (!empty($this->unit) ? " {$this->unit}" : '');
            } else {
                return $result;
            }
        } else {
            return parent::__get($name);
        }
    }

    /**
     * Phương thức chuyển đổi số thành chữ số.
     * Nó được trích từ: https://developers.mynukeviet.net/code/Ham-PHP-chuyen-doi-chu-so-ra-chu-44/
     *
     * @param mixed $number Số cần chuyển sang chữ.
     * @param bool $ucfirst Thiết lập nếu như bạn muốn tự động viết hoa chữ cái đầu.
     * @return bool|string Trả về false
     * @throws InvalidConfigException
     */
    protected function n2w($number, $ucfirst = true)
    {
        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
            throw new InvalidConfigException('Only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX);
        } elseif ($number < 0) {
            return self::NEGATIVE . $this->n2w(abs($number), false);
        }

        $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = self::DICTIONARY[$number];
                break;
            case $number < 100:
                $tens = ((int)($number / 10)) * 10;
                $units = $number % 10;
                $string = self::DICTIONARY[$tens];
                if ($units) {
                    $string .= self::HYPHEN . self::DICTIONARY[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = self::DICTIONARY[$hundreds] . ' ' . self::DICTIONARY[100];
                if ($remainder) {
                    $string .= self::CONJUNCTION . $this->n2w($remainder, $ucfirst);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->n2w($numBaseUnits, $ucfirst) . ' ' . self::DICTIONARY[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? self::CONJUNCTION : self::SEPARATOR;
                    $string .= $this->n2w($remainder, $ucfirst);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= self::DECIMAL;
            $words = [];
            foreach (str_split((string)$fraction) as $number) {
                $words[] = self::DICTIONARY[$number];
            }
            $string .= implode(' ', $words);
        }

        if ($ucfirst) {
            return ucfirst(mb_strtolower($string, 'utf8'));
        } else {
            return $string;
        }
    }


}
