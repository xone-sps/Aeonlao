<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 6/1/2019
 * Time: 8:32 PM
 */

namespace App\Responses\Admin\Schema;


class AnswerContentSchema
{

    protected $question;
    protected $access;
    protected $schema;

    /**
     * AnswerContentSchema constructor.
     * @param QuestionContentSchema $question
     * @param AccessAnswerSchema $access
     */
    public function __construct(QuestionContentSchema $question, AccessAnswerSchema $access)
    {
        $this->question = $question->toArray();
        $this->access = $access;
    }

    /**
     * @return array
     */
    public function getQuestion(): array
    {
        return $this->question;
    }

    /**
     * @return AccessAnswerSchema
     */
    public function getAccess(): AccessAnswerSchema
    {
        return $this->access;
    }

    /**
     * @return mixed
     */
    public function getSchema()
    {
        return $this->schema;
    }

    public function build($rawAnswer): void
    {
        $question = $this->question;
        $answer = null;
        if (in_array($question['types'], ($answer = new TextAnswerSchema($question['content']))->allowTypes(), true)) {
            $answer->build($rawAnswer);
            $this->schema = $answer->toArray();
        }

        if ($question['types'] === 'multiple_choice' || $question['types'] === 'dropdown_list') {
            $answer = new MultipleAnswer($question['content']);
            $answer->build($rawAnswer);
            $this->schema = $answer->toArray();
        }

        if ($question['types'] === 'priority') {
            $answer = new PriorityAnswer($question['content']);
            $answer->build($rawAnswer);
            $this->schema = $answer->toArray();
        }

        if ($question['types'] === 'checkboxes') {
            $answer = new CheckboxesAnswer($question['content']);
            $answer->build($rawAnswer);
            $this->schema = $answer->toArray();
        }
        if ($question['types'] === 'linear_scale') {
            $answer = new LinearScaleAnswer($question['content']);
            $answer->build($rawAnswer);
            $this->schema = $answer->toArray();
        }
        if ($question['types'] === 'matrix_scale') {
            $answer = new MatrixScaleAnswer($question['content']);
            $answer->build($rawAnswer);//['en' => ['skills_rage' => 'Adopting Modern Technology']]
            $this->schema = $answer->toArray();
        }
    }

    public function toJson()
    {
        return json_encode([
            'question' => $this->question,
            'access' => $this->access->toArray(),
            'schema' => $this->schema
        ]);
    }
    public function toArray()
    {
        return [
            'question' => $this->question,
            'access' => $this->access->toArray(),
            'schema' => $this->schema
        ];
    }
}

class TextAnswerSchema
{
    protected $content = [];
    protected $text_answer = ['en' => ''];

    public function allowTypes(): array
    {
        return [
            'short_answer',
            'paragraph'
        ];
    }

    /**
     * TextAnswerSchema constructor.
     * @param $content
     */
    public function __construct($content)
    {
        $this->content = $content ?? [];
    }

    public function build($rawText)
    {
        foreach ($this->content as $data) {
            $this->text_answer[$data['language']] = $rawText[$data['language']] ?? '';
        }
    }

    public function toArray(): array
    {
        return $this->text_answer;
    }

}

class MultipleAnswer
{
    protected $content = [];
    protected $selected = ['en' => ''];

    /**
     * MultipleAnswer constructor.
     * @param $content
     */
    public function __construct($content)
    {
        $this->content = $content ?? [];
    }

    public function build($rawSelected)
    {
        foreach ($this->content as $data) {
            $options = array_column($data['option_answers'], 'description');
            $rawSelectedLang = $rawSelected[$data['language']] ?? '';
            if (in_array($rawSelectedLang, $options, true)) {
                $this->selected[$data['language']] = $rawSelectedLang;
            } else {
                $this->selected[$data['language']] = '';
            }
        }
    }

    public function toArray(): array
    {
        return $this->selected;
    }

}

class CheckboxesAnswer
{
    protected $content = [];
    protected $selected = ['en' => []];

    /**
     * CheckboxesAnswer constructor.
     * @param $content
     */
    public function __construct($content)
    {
        $this->content = $content ?? [];
    }

    public function build($rawSelected)
    {
        foreach ($this->content as $data) {
            $options = array_column($data['option_answers'], 'description');
            $rawSelectedLang = $rawSelected[$data['language']] ?? [];
            foreach ($rawSelectedLang as $value) {
                if (in_array($value, $options, true)) {
                    $this->selected[$data['language']][] = $value;
                } else {
                    $this->selected[$data['language']][] = '';
                }
            }
        }
    }

    public function toArray(): array
    {
        return $this->selected;
    }

}

class  PriorityAnswer
{
    protected $content = [];
    protected $selected = ['en' => []];

    /**
     * PriorityAnswer constructor.
     * @param array $content
     */
    public function __construct(array $content)
    {
        $this->content = $content ?? [];
    }

    public function build($rawSelected)
    {
        foreach ($this->content as $data) {
            $options = is_array($data['option_answers']) ? array_column($data['option_answers'], 'description') : [];
            foreach ($options as $key => $option) {
                $answer = $rawSelected[$data['language']] ?? [];
                if (is_array($answer) && count($answer) > 0) {
                    $this->selected[$data['language']][$this->getKey($option)] = $answer[$this->getKey($option)] ?? null;
                } else {
                    $this->selected[$data['language']][$this->getKey($option)] = null;
                }
            }
        }
    }

    public function getKey($title): string
    {
        return strtolower(str_replace(' ', '_', $title));
    }

    public function toArray(): array
    {
        return $this->selected;
    }
}

class  LinearScaleAnswer
{
    protected $content = [];
    protected $selected = ['en' => null];

    /**
     * LinearScaleAnswer constructor.
     * @param array $content
     */
    public function __construct(array $content)
    {
        $this->content = $content ?? [];
    }

    public function build($rawSelected)
    {
        foreach ($this->content as $data) {
            $line = is_array($data['line_answer']) ? $data['line_answer'] : [];
            $options = [];
            if ($line['line_value'] >= 0 && $line['line_end_value'] <= 50) {
                for ($i = $line['line_value'], $max = $line['line_end_value']; $i <= $max; $i++) {
                    $options[] = $i;
                }
                if (in_array($rawSelected[$data['language']]??null, $options, true)) {
                    $this->selected[$data['language']] = $rawSelected[$data['language']];
                } else {
                    $this->selected[$data['language']] = null;
                }
            }
        }
    }

    public function toArray(): array
    {
        return $this->selected;
    }
}

class MatrixScaleAnswer
{
    protected $content = [];
    protected $selected = ['en' => null];

    /**
     * MatrixScaleAnswer constructor.
     * @param array $content
     */
    public function __construct(array $content)
    {
        $this->content = $content;
    }


    public function build($rawSelected)
    {
        foreach ($this->content as $data) {

            $column_options = is_array($data['option_answers']) ? array_column($data['option_answers'], 'description') : [];
            $row_options = is_array($data['row_option_answers']) ? array_column($data['row_option_answers'], 'description') : [];

            foreach ($row_options as $key => $row_option) {
                $answer = $rawSelected[$data['language']] ?? [];
                if (is_array($answer) && count($answer) > 0) {
                    $answer_column = $answer[$this->getKey($row_option)] ?? null;
                    if (in_array($answer_column, $column_options, true)) {
                        $this->selected[$data['language']][$this->getKey($row_option)] = $answer_column;
                    } else {
                        $this->selected[$data['language']][$this->getKey($row_option)] = null;
                    }
                } else {
                    $this->selected[$data['language']][$this->getKey($row_option)] = null;
                }
            }
        }
    }

    public function getKey($title): string
    {
        return strtolower(str_replace(' ', '_', $title));
    }

    public function toArray(): array
    {
        return $this->selected;
    }
}

class AccessAnswerSchema
{
    protected $sectionIndex = -1;
    protected $questionIndex = -1;
    protected $questionContentIndex = -1;
    protected $updated_at;
    protected $questionId;
    /**
     * @return int
     */
    public function getQuestionContentIndex(): int
    {
        return $this->questionContentIndex;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return mixed
     */
    public function getQuestionId()
    {
        return $this->questionId;
    }

    /**
     * @param mixed $questionId
     */
    public function setQuestionId($questionId): void
    {
        $this->questionId = $questionId;
    }

    /**
     * @param int $questionContentIndex
     */
    public function setQuestionContentIndex(int $questionContentIndex): void
    {
        $this->questionContentIndex = $questionContentIndex ?? -1;
    }

    /**
     * @return int
     */
    public function getSectionIndex(): int
    {
        return $this->sectionIndex;
    }

    /**
     * @param int $sectionIndex
     */
    public function setSectionIndex(int $sectionIndex): void
    {
        $this->sectionIndex = $sectionIndex ?? -1;
    }

    /**
     * @return int
     */
    public function getQuestionIndex(): int
    {
        return $this->questionIndex;
    }

    /**
     * @param int $questionIndex
     */
    public function setQuestionIndex(int $questionIndex): void
    {
        $this->questionIndex = $questionIndex ?? -1;
    }


    public function build($raw): void
    {
        if (is_object($raw)) {
            $this->sectionIndex = $raw->sectionIndex ?? -1;
            $this->questionIndex = $raw->questionIndex ?? -1;
            $this->questionContentIndex = $raw->questionContentIndex ?? -1;
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
