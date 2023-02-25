<?php

class Table
{
    /**
     * @purpose 头部表格标题
     * @param array $data 待处理数据
     * @return array
     */
    public function getTable(array $data): array
    {
        $table[] = $data[1]->metadata->cols[0]->rows[0]->label;
        // 将对象转为数组
        $title = $data[1]->metadata->cols[1]->rows;
        foreach ($title as $key => $value) {
            foreach ((array)$value as $val) {
                $title[$key] = $val;
            }
        }

        $table[] = $title;
        $codeData = $data[1]->data;
        foreach ($codeData as $value) {
            $temp = [];
            foreach ((array)$value as $val) {
                $decode = html_entity_decode($val);
                if (strlen($decode) === 9) {
                    $decode = substr($decode, 0, 3) . '&nbsp;' . substr($decode, 3, 3) . '&nbsp;' . substr($decode, 6, 3);
                }
                $temp[] = html_entity_decode($decode);
            }
            $table[] = $temp;
        }
        return $table;
    }

    /**
     * @purpose 输出表格
     * @param array $data 待处理数据
     * @return void
     */
    public function printTable(array $data)
    {
        $topMain = explode("<br>", $data[0]);
        if (empty($data)) {
            return;
        }

        unset($data[0]);
        $data = array_values($data);
        // 计算列数和列宽
        $colWidth = self::colWidth($data);
        // 输出分隔线
        foreach ($colWidth as $width) {
            printf("+-%s-", str_repeat("-", $width));
        }
        printf("+\n");
        // 输出表头
        self::tableHeader($colWidth, $topMain, $data);
        unset($data[0]);
        // 输出分隔线
        foreach ($colWidth as $width) {
            printf("+-%s-", str_repeat("-", $width));
        }
        printf("+\n");

        self::tableDataLine($data);

        // 输出分隔线
        foreach ($colWidth as $width) {
            printf("+-%s-", str_repeat("-", $width));
        }
        printf("+\n");
    }

    /**
     * @purpose 计算列数和列宽
     * @param array $data 待计算数据
     * @return array
     */
    private static function colWidth(array $data): array
    {
        $colCount = count($data[0]);
        $colWidth = array_fill(0, $colCount, 0);
        foreach ($data as $row) {
            foreach ($row as $colIndex => $colValue) {
                $colWidth[$colIndex] = max($colWidth[$colIndex], strlen($colValue));
            }
        }
        return $colWidth;
    }

    /**
     * @purpose 控制台输出表头
     * @param array $colWidth 列数和列宽
     * @param array $topMain 顶部大标题
     * @param array $data 待处理数据
     * @return void
     */
    private static function tableHeader(array $colWidth, array $topMain, array $data)
    {
        foreach ($topMain as $key => $value) {
            $number = $key === 0 ? 100 : 93;
            printf("|");
            $padding = str_repeat(' ', ($number - strlen($value)) / 2);
            printf("%s%s%s |", $padding, $value, $padding);
            printf("\n");
        }
        foreach ($colWidth as $width) {
            printf("+-%s-", str_repeat("-", $width));
        }
        printf("+\n");

        // 标题
        printf("|");
        foreach ($data[0] as $colIndex => $colValue) {
            $length = 0;
            switch ($colIndex) {
                case 0:
                    $length = 16;
                    break;
                case 1:
                    $length = 17;
                    break;
                case 3:
                case 2:
                    $length = 24;
                    break;
                case 4:
                    $length = 16;
            }
            printf(" %-{$length}s |", $colValue);
        }
        printf("\n");
    }

    /**
     * @purpose 表格输出数据行
     * @param array $data 待处理数据
     * @return void
     */
    private static function tableDataLine(array $data)
    {
        // 输出数据行
        foreach ($data as $row) {
            printf("|");
            foreach ($row as $colIndex => $colValue) {
                $length = 0;
                switch ($colIndex) {
                    case 4:
                    case 0:
                        $length = 12;
                        break;
                    case 1:
                        $length = 17;
                        break;
                    case 3:
                    case 2:
                        $length = 18;
                        break;
                }
                if (strlen($colValue) === 13) {
                    printf(" %-{$length}s  |", $colValue);
                } else {
                    printf(" %-{$length}s |", $colValue);
                }
            }
            printf("\n");
        }
    }
}