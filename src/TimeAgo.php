<?php
/**
 * Time-Ago Library
 *
 * A library for displaying time intervals in a human-readable format.
 *
 * @category  Library
 * @package   TimeAgo
 * @author    Ramazan Çetinkaya <https://github.com/ramazancetinkaya>
 * @license   MIT License <https://opensource.org/licenses/MIT>
 * @version   1.0
 * @link      https://github.com/ramazancetinkaya/time-ago
 */

/**
 * Class TimeAgo
 *
 * Provides a way to display time intervals in a human-readable format.
 */
class TimeAgo
{
    /**
     * @var array Associative array of language translations.
     */
    private array $translations = [

        // Translations for English
        'en' => [
            'year' => ['year', 'years'],
            'month' => ['month', 'months'],
            'week' => ['week', 'weeks'],
            'day' => ['day', 'days'],
            'hour' => ['hour', 'hours'],
            'minute' => ['minute', 'minutes'],
            'second' => ['second', 'seconds'],
            'ago' => 'ago',
            'just_now' => 'just now',
        ],

        // Translations for Turkish
        'tr' => [
            'year' => ['yıl', 'yıl'],
            'month' => ['ay', 'ay'],
            'week' => ['hafta', 'hafta'],
            'day' => ['gün', 'gün'],
            'hour' => ['saat', 'saat'],
            'minute' => ['dakika', 'dakika'],
            'second' => ['saniye', 'saniye'],
            'ago' => 'önce',
            'just_now' => 'az önce',
        ],
         
        // Translations of other languages..
    ];

    /**
     * @var string The default language to use.
     */
    private string $defaultLanguage = 'en';

    /**
     * @var string The selected language to use.
     */
    private string $language;

    /**
     * @var bool Whether the language support is enabled or not.
     */
    private bool $languageSupportEnabled;

    /**
     * TimeAgo constructor.
     *
     * @param string|null $language The selected language to use. (Optional)
     * @param bool $languageSupportEnabled Whether the language support is enabled or not. (Optional)
     */
    public function __construct(?string $language = null, bool $languageSupportEnabled = true)
    {
        $this->language = $language ?? $this->defaultLanguage;
        $this->languageSupportEnabled = $languageSupportEnabled;
    }

    /**
     * Returns the time ago string based on the given timestamp.
     *
     * @param int $timestamp The timestamp to convert to a time ago string.
     * @return string The time ago string.
     * @throws Exception When the selected language is not supported.
     */
    public function getTimeAgo(int $timestamp): string
    {
        if ($this->languageSupportEnabled) {
            if (!isset($this->translations[$this->language])) {
                throw new Exception("Language '{$this->language}' is not supported.");
            }

            $translations = $this->translations[$this->language];
        } else {
            if ($this->language !== 'en') {
                $this->displayWarning("Language support is disabled. Using the default language '{$this->defaultLanguage}'.");
            }

            $translations = $this->translations[$this->defaultLanguage];
        }

        $currentTimestamp = time();
        $timeDifference = $currentTimestamp - $timestamp;

        if ($timeDifference < 60) {
            return $translations['just_now'];
        } elseif ($timeDifference < 3600) {
            $minutes = intdiv($timeDifference, 60);
            return $this->getTimeAgoString($minutes, $translations['minute'], $translations['ago']);
        } elseif ($timeDifference < 86400) {
            $hours = intdiv($timeDifference, 3600);
            return $this->getTimeAgoString($hours, $translations['hour'], $translations['ago']);
        } elseif ($timeDifference < 604800) {
            $days = intdiv($timeDifference, 86400);
            return $this->getTimeAgoString($days, $translations['day'], $translations['ago']);
        } elseif ($timeDifference < 2592000) {
            $weeks = intdiv($timeDifference, 604800);
            return $this->getTimeAgoString($weeks, $translations['week'], $translations['ago']);
        } elseif ($timeDifference < 31536000) {
            $months = intdiv($timeDifference, 2592000);
            return $this->getTimeAgoString($months, $translations['month'], $translations['ago']);
        } else {
            $years = intdiv($timeDifference, 31536000);
            return $this->getTimeAgoString($years, $translations['year'], $translations['ago']);
        }
    }

    /**
     * Returns the time ago string based on the quantity and translations.
     *
     * @param int $quantity The quantity of time.
     * @param array $translations The translations for the time unit.
     * @param string $suffix The suffix for the time ago string.
     * @return string The time ago string.
     */
    private function getTimeAgoString(int $quantity, array $translations, string $suffix): string
    {
        $unit = ($quantity === 1) ? $translations[0] : $translations[1];
        return "$quantity {$unit} {$suffix}";
    }

    /**
     * Displays a warning message.
     *
     * @param string $message The warning message.
     */
    private function displayWarning(string $message): void
    {
        trigger_error($message, E_USER_WARNING);
    }
}
