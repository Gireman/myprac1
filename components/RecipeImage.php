<?php

namespace app\components;

use Yii;

/**
 * Работа с картинками рецептов.
 *
 * Сайт ждёт для каждого рецепта четыре файла в web/img:
 *   {имя}-400.webp, {имя}-800.webp, {имя}-1200.webp и {имя}-800.jpg
 * (см. views/site/recipe.php и _recipe_card.php). В БД хранится только {имя}.
 * Из одной загруженной картинки класс делает все четыре файла.
 */
class RecipeImage
{
    /** Ширины, под которые сайт делает srcset */
    const WIDTHS = [400, 800, 1200];

    /** Префикс имён, загруженных через админку (по нему же чистим файлы при замене/удалении) */
    const PREFIX = 'recipe-';

    /**
     * Папка с картинками.
     *
     * @return string
     */
    public static function dir()
    {
        return rtrim(Yii::getAlias('@webroot/img'), '/\\');
    }

    /**
     * Имена всех картинок, для которых есть файл {имя}-800.jpg.
     *
     * @return string[]
     */
    public static function existing()
    {
        $names = [];
        foreach (glob(self::dir() . '/*-800.jpg') ?: [] as $file) {
            $names[] = basename($file, '-800.jpg');
        }
        sort($names);
        return $names;
    }

    /**
     * Есть ли картинка с таким именем.
     *
     * @param string $base
     * @return bool
     */
    public static function exists($base)
    {
        return (bool)preg_match('/^[A-Za-z0-9_-]+$/', (string)$base)
            && is_file(self::dir() . '/' . $base . '-800.jpg');
    }

    /**
     * Создаёт четыре файла из загруженной картинки и возвращает имя без суффиксов.
     *
     * @param string $sourceFile путь к временному файлу
     * @return string
     * @throws \RuntimeException с текстом, который можно показать пользователю
     */
    public static function generate($sourceFile)
    {
        if (!function_exists('imagecreatetruecolor') || !function_exists('imagewebp')) {
            throw new \RuntimeException('На сервере не включено расширение GD с поддержкой WebP — загрузка картинок недоступна. Выберите готовую картинку из списка.');
        }

        $source = self::open($sourceFile);
        $srcW = imagesx($source);
        $srcH = imagesy($source);

        $dir = self::dir();
        if (!is_dir($dir) || !is_writable($dir)) {
            imagedestroy($source);
            throw new \RuntimeException('Папка web/img недоступна для записи.');
        }

        do {
            $base = self::PREFIX . bin2hex(random_bytes(4));
        } while (is_file($dir . '/' . $base . '-800.jpg'));

        $created = [];
        try {
            foreach (self::WIDTHS as $width) {
                // Не увеличиваем маленький оригинал
                $w = min($width, $srcW);
                $h = max(1, (int)round($srcH * $w / $srcW));

                $copy = imagecreatetruecolor($w, $h);
                imagefill($copy, 0, 0, imagecolorallocate($copy, 255, 255, 255));
                imagecopyresampled($copy, $source, 0, 0, 0, 0, $w, $h, $srcW, $srcH);

                $webp = $dir . '/' . $base . '-' . $width . '.webp';
                if (!@imagewebp($copy, $webp, 82)) {
                    imagedestroy($copy);
                    throw new \RuntimeException('Не удалось сохранить изображение.');
                }
                $created[] = $webp;

                if ($width === 800) {
                    $jpg = $dir . '/' . $base . '-800.jpg';
                    imageinterlace($copy, true);
                    if (!@imagejpeg($copy, $jpg, 86)) {
                        imagedestroy($copy);
                        throw new \RuntimeException('Не удалось сохранить изображение.');
                    }
                    $created[] = $jpg;
                }
                imagedestroy($copy);
            }
        } catch (\Throwable $e) {
            foreach ($created as $file) {
                @unlink($file);
            }
            imagedestroy($source);
            throw $e;
        }

        imagedestroy($source);
        return $base;
    }

    /**
     * Удаляет файлы картинки.
     *
     * @param string $base
     */
    public static function delete($base)
    {
        if (!preg_match('/^[A-Za-z0-9_-]+$/', (string)$base)) {
            return;
        }
        foreach (self::WIDTHS as $width) {
            @unlink(self::dir() . '/' . $base . '-' . $width . '.webp');
        }
        @unlink(self::dir() . '/' . $base . '-800.jpg');
    }

    /**
     * Читает jpg/png/webp в GD-ресурс (с учётом поворота из EXIF).
     *
     * @param string $file
     * @return \GdImage|resource
     */
    private static function open($file)
    {
        $info = @getimagesize($file);
        if (!$info) {
            throw new \RuntimeException('Файл не похож на изображение.');
        }

        switch ($info[2]) {
            case IMAGETYPE_JPEG:
                $image = @imagecreatefromjpeg($file);
                break;
            case IMAGETYPE_PNG:
                $image = @imagecreatefrompng($file);
                break;
            case IMAGETYPE_WEBP:
                $image = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($file) : false;
                break;
            default:
                $image = false;
        }
        if (!$image) {
            throw new \RuntimeException('Не удалось прочитать изображение. Подойдут JPG, PNG или WebP.');
        }

        if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
            $exif = @exif_read_data($file);
            $rotate = [3 => 180, 6 => -90, 8 => 90];
            if (!empty($exif['Orientation']) && isset($rotate[$exif['Orientation']])) {
                $rotated = imagerotate($image, $rotate[$exif['Orientation']], 0);
                if ($rotated) {
                    imagedestroy($image);
                    $image = $rotated;
                }
            }
        }

        return $image;
    }
}
