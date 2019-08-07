<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 5/22/2019
 * Time: 1:01 PM
 */

namespace App\Responses\Admin\Schema;


class QuestionContentSchema implements QuestionSchemaInterface
{
    protected $types = 'multiple_choice';
    protected $is_required = false;
    protected $content = [];

    /**
     * QuestionContentSchema constructor.
     */
    public function __construct()
    {
        $this->content[] = $this->singleContent()->toArray();
    }


    public function allowTypes(): array
    {
        return [
            'short_answer',
            'paragraph',
            'multiple_choice',
            'checkboxes',
            'dropdown_list',
            'linear_scale',
            'matrix_scale',
            'priority',
        ];
    }

    public function singleContent(): SingleQuestionContent
    {
        //option_answers
        return new SingleQuestionContent();
    }

    public function build($questionRaw): void
    {
        if (is_object($questionRaw)) {
            if (in_array($questionRaw->types ?? 'multiple_choice', $this->allowTypes(), true)) {
                $this->types = $questionRaw->types;
            }
            $this->is_required = $questionRaw->is_required ?? false;
            $rawContents = $questionRaw->content ?? '';
            if (is_array($rawContents)) {
                $contents = [];
                foreach ($rawContents as $key => $rawContent) {
                    $singleContent = $this->singleContent();
                    $singleContent->build($rawContent);
                    $contents[] = $singleContent->toArray();
                }
                if (count($contents) > 0) {
                    $this->content = $contents;
                }
            }
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }
}

class SingleQuestionContent
{
    protected $language = 'en';
    protected $title = '';
    protected $option_answers = [];
    protected $row_option_answers = [];
    protected $line_answer;
    protected $text_answer = '';

    /**
     * SingleQuestionContent constructor.
     */
    public function __construct()
    {
        $this->option_answers[] = (new OptionAnswer())->toArray();
        $this->row_option_answers[] = (new OptionAnswer())->toArray();
        $this->line_answer = (new LineAnswer())->toArray();
    }


    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function getOptionAnswers(): array
    {
        return $this->option_answers;
    }

    /**
     * @return array
     */
    public function getRowOptionAnswers(): array
    {
        return $this->row_option_answers;
    }

    /**
     * @return mixed
     */
    public function getLineAnswer()
    {
        return $this->line_answer;
    }

    /**
     * @return string
     */
    public function getTextAnswer(): string
    {
        return $this->text_answer;
    }

    public function build($raw): void
    {
        if (is_object($raw)) {
            //content
            $this->language = $raw->language ?? 'en';
            $this->title = $raw->title ?? '';
            //line answer
            $lineAnswer = new LineAnswer();
            $lineAnswer->build($raw->line_answer ?? '');
            $this->line_answer = $lineAnswer->toArray();
            //option answers
            $option_answers = [];
            $raw_option_answers = $raw->option_answers ?? '';
            if (is_array($raw_option_answers)) {
                foreach ($raw_option_answers as $raw_option_answer) {
                    $option_answer = new OptionAnswer();
                    if (is_object($raw_option_answer)) {
                        $option_answer->setDescription($raw_option_answer->description ?? 'Option 1');
                    }
                    $option_answers[] = $option_answer->toArray();
                }
            }
            if (count($option_answers) <= 0) {
                $option_answer = new OptionAnswer();
                $option_answers[] = $option_answer->toArray();
            }
            $this->option_answers = $option_answers;
            //row option answers
            $row_option_answers = [];
            $raw_row_option_answers = $raw->row_option_answers ?? '';
            if (is_array($raw_row_option_answers)) {
                foreach ($raw_row_option_answers as $raw_row_option_answer) {
                    $row_option_answer = new OptionAnswer();
                    if (is_object($raw_row_option_answer)) {
                        $row_option_answer->setDescription($raw_row_option_answer->description ?? 'Option 1');
                    }
                    $row_option_answers[] = $row_option_answer->toArray();
                }
            }
            if (count($row_option_answers) <= 0) {
                $row_option_answer = new OptionAnswer();
                $row_option_answers[] = $row_option_answer->toArray();
            }
            $this->row_option_answers = $row_option_answers;
            $this->text_answer = $raw->text_answer ?? '';
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

class LineAnswer
{
    protected $line_value = 1;
    protected $line_end_value = 5;
    protected $line_tag = '';
    protected $line_end_tag = '';

    /**
     * @return int
     */
    public function getLineValue(): int
    {
        return $this->line_value;
    }

    /**
     * @return int
     */
    public function getLineEndValue(): int
    {
        return $this->line_end_value;
    }

    /**
     * @return string
     */
    public function getLineTag(): string
    {
        return $this->line_tag;
    }

    /**
     * @return string
     */
    public function getLineEndTag(): string
    {
        return $this->line_end_tag;
    }

    public function build($raw): void
    {
        if (is_object($raw)) {
            $this->line_value = $raw->line_value ?? 0;
            $this->line_end_value = $raw->line_end_value ?? '';

            $this->line_tag = $raw->line_tag ?? '';
            $this->line_end_tag = $raw->line_end_tag ?? '';
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

class OptionAnswer
{
    protected $description = 'Option 1';

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
