<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 2/27/2019
 * Time: 10:29 AM
 */

namespace App\Responses\User;

use App\Models\Assessment;
use App\Models\CheckAssessment;
use App\Models\CheckAssessmentFieldInspector;
use App\User;
use Illuminate\Contracts\Support\Responsable;
use PhpOffice\PhpWord;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\Shared\Converter;

class UserFileDownloadResponse implements Responsable
{

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        try {
            $checkAssessment = $this->getCheckAssessment($request);
            if (!$checkAssessment) {
                return redirect()->route('get.home.index');
            }
            // Saving the document as OOXML file...
            $phpWord = $this->createFileMsWord($checkAssessment);
            $hash = md5(uniqid('assessment-uniq', true));
            $filename = public_path("assets/files/downloads/assessment-{$hash}.docx");
            $objWriter = PhpWord\IOFactory::createWriter($phpWord);
            $objWriter->save($filename);
            return response()->download($filename);
        } catch (PhpWord\Exception\Exception $e) {
            Log::info("Export MsWord File: {$e->getMessage()}");
        }
        return redirect()->route('get.home.index');
    }

    public function getCheckAssessment($request)
    {
        $user = $user = $request->user();
        if ($user->hasActions('download-export-other')) {
            if ($request->type === 'institute') {
                $checkAssessment = CheckAssessment::find($request->id);
                $assessment = Assessment::find($checkAssessment->assessment_id ?? null);
                if (isset($assessment)) {
                    return [
                        'title' => $assessment->title,
                        'description' => $assessment->description,
                        'origin' => $assessment,
                        'check' => $checkAssessment,
                        'self' => User::find($checkAssessment->user_id),
                        'related_user' => false,
                    ];
                }
            } else if ($request->type === 'field_inspector') {
                $checkAssessmentFieldInspector = CheckAssessmentFieldInspector::find($request->id);
                $checkAssessment = CheckAssessment::find($checkAssessmentFieldInspector->check_assessment_id ?? null);
                $assessment = Assessment::find($checkAssessment->assessment_id ?? null);
                if (isset($assessment)) {
                    return [
                        'title' => $assessment->title,
                        'description' => $assessment->description,
                        'origin' => $assessment,
                        'check' => $checkAssessmentFieldInspector,
                        'self' => User::find($checkAssessment->user_id),
                        'related_user' => User::find($checkAssessmentFieldInspector->check_user_id),
                    ];
                }
            }

        } else if ($request->type === 'institute') {
            $checkAssessment = CheckAssessment::where('user_id', $user->id)->where('id', $request->id)->first();
            if (isset($checkAssessment)) {
                $assessment = Assessment::find($checkAssessment->assessment_id);

                return [
                    'title' => $assessment->title,
                    'description' => $assessment->description,
                    'origin' => $assessment,
                    'check' => $checkAssessment,
                    'self' => User::find($checkAssessment->user_id),
                    'related_user' => false,
                ];
            }
        } else if ($request->type === 'field_inspector') {
            $checkAssessmentFieldInspector = CheckAssessmentFieldInspector::where('field_inspector_id', $user->id)->where('id', $request->id)->first();
            $checkAssessment = CheckAssessment::find($checkAssessmentFieldInspector->check_assessment_id ?? null);
            $assessment = Assessment::find($checkAssessment->assessment_id ?? null);
            if (isset($assessment)) {
                return [
                    'title' => $assessment->title,
                    'description' => $assessment->description,
                    'origin' => $assessment,
                    'check' => $checkAssessmentFieldInspector,
                    'self' => User::find($checkAssessment->user_id),
                    'related_user' => User::find($checkAssessmentFieldInspector->check_user_id),
                ];
            }
        }

        return false;
    }

    public function createFileMsWord($checkAssessment)
    {

        $laoFontName = 'Saysettha OT';
        // Creating the new document...
        $phpWord = new PhpWord\PhpWord();
        /* Note: any element you append to a document must reside inside of a Section. */

        // Adding an empty Section to the document...
        $section = $phpWord->addSection();
        $header = $section->addHeader();
        $textrun = $header->addTextRun();
        if (!$checkAssessment['related_user']) {
            $textrun->addText($checkAssessment['self']->name . "'s assessment'", array('name' => $laoFontName, 'bold' => true));
        } else {
            $textrun->addText($checkAssessment['self']->name . "'s assessment' for " . $checkAssessment['related_user']->name, array('name' => $laoFontName, 'bold' => true));
        }
        $section->addText($checkAssessment['title'],
            array('name' => $laoFontName, 'size' => 13, 'bold' => true, 'color' => '363636')
        );
        $section->addText($checkAssessment['description'],
            array('name' => $laoFontName, 'size' => 11, 'color' => '363636')
        );

        $fontStyleQTitleName = 'F-Style-Question-Title';
        $phpWord->addFontStyle($fontStyleQTitleName, ['name' => $laoFontName, 'size' => 12, 'bold' => true, 'color' => '1f2d3d']);
        $fontStyleATitleName = 'F-Style-Answer-Title';
        $phpWord->addFontStyle($fontStyleATitleName, ['size' => 12, 'name' => $laoFontName, 'color' => '1f2d3d']);
        $paragraphStyleName = 'P-Style';
        $phpWord->addParagraphStyle($paragraphStyleName, array('spaceAfter' => 95));
        $multilevelNumberingStyleName = 'multilevel';
        //Options:decimal,upperRoman,lowerRoman,upperLetter,lowerLetter,ordinal,cardinalText,ordinalText,hex,chicago,ideographDigital,japaneseCounting,aiueo,iroha,decimalFullWidth,decimalHalfWidth,japaneseLegal,japaneseDigitalTenThousand,decimalEnclosedCircle,decimalFullWidth2,aiueoFullWidth,irohaFullWidth,decimalZero,bullet,ganada,chosung,decimalEnclosedFullstop,decimalEnclosedParen,decimalEnclosedCircleChinese,ideographEnclosedCircle,ideographTraditional,ideographZodiac,ideographZodiacTraditional,taiwaneseCounting,ideographLegalTraditional,taiwaneseCountingThousand,taiwaneseDigital,chineseCounting,chineseLegalSimplified,chineseCountingThousand,koreanDigital,koreanCounting,koreanLegal,koreanDigital2,vietnameseCounting,russianLower,russianUpper,none,numberInDash,hebrew1,hebrew2,arabicAlpha,arabicAbjad,hindiVowels,hindiConsonants,hindiNumbers,hindiCounting,thaiLetters,thaiNumbers,thaiCounting
        // Numbered heading
        $headingNumberingStyleName = 'headingNumbering';
        $phpWord->addNumberingStyle(
            $headingNumberingStyleName,
            array('type' => 'multilevel',
                'levels' => array(
                    array('pStyle' => 'Heading1', 'format' => 'upperRoman', 'text' => '%1.', 'left' => 360, 'hanging' => 360,),
                    array('pStyle' => 'Heading2', 'format' => 'upperRoman', 'text' => '%1.%2.', 'left' => 360, 'hanging' => 360,),
                    array('pStyle' => 'Heading3', 'format' => 'upperRoman', 'text' => '%1.%2.%3.', 'left' => 360, 'hanging' => 360,),
                ),
            )
        );
        $phpWord->addTitleStyle(1, array('size' => 14, 'name' => $laoFontName), array('numStyle' => $headingNumberingStyleName, 'numLevel' => 0));
        $phpWord->addTitleStyle(2, array('size' => 12, 'name' => $laoFontName), array('numStyle' => $headingNumberingStyleName, 'numLevel' => 1));
        $phpWord->addTitleStyle(3, array('size' => 11, 'name' => $laoFontName), array('numStyle' => $headingNumberingStyleName, 'numLevel' => 2));

        // Section Title & Description 92498562
        $section->addTextBreak();
        $mSections = $checkAssessment['origin']->sections;
        $checkSections = $checkAssessment['check']->sections();

        foreach ($mSections as $sKey => $mSection) {
            $section->addTitle($mSection->title);
            $section->addText($mSection->description, array('size' => 10, 'name' => $laoFontName, 'color' => '1f2d3d'), ['indent' => 0.5]);
            $section->addTextBreak();
            $checkQuestions = $checkSections[$sKey]->checkAssessmentSectionQuestions;

            $sectionMultilevelNumberingStyleName = "$multilevelNumberingStyleName-$sKey";
            $phpWord->addNumberingStyle(
                $sectionMultilevelNumberingStyleName,
                array(
                    'type' => 'multilevel',
                    'levels' => array(
                        array('format' => 'decimal', 'text' => '%1.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
                        array('format' => 'decimal', 'text' => '%1.', 'left' => 1080, 'hanging' => 360, 'tabPos' => 1080),
                    ),
                )
            );

            foreach ($checkQuestions as $qKey => $question) {
                $mCheckQuestion = $question->toJsonDecode();
                $mQuestion = $mCheckQuestion->question;
                $mContents = $mQuestion->content;
                $answer = $question->getAnswer($mCheckQuestion);//default language is en
                foreach ($mContents as $cKey => $mContent) {
                    $section->addListItem($mContent->title, 0, $fontStyleQTitleName, $sectionMultilevelNumberingStyleName, $paragraphStyleName);
                    if (is_array($answer)) {
                        $multilevelNumberingStyleNameAnswer = "multilevel-$sKey-$qKey-$cKey";
                        $phpWord->addNumberingStyle(
                            $multilevelNumberingStyleNameAnswer,
                            array(
                                'type' => 'multilevel',
                                'levels' => array(
                                    array('format' => 'decimal', 'text' => '%1.', 'left' => 1080, 'hanging' => 360, 'tabPos' => 1080)
                                )
                            )
                        );
                        foreach ($answer as $text) {
                            $section->addListItem($text, 0, $fontStyleATitleName, $multilevelNumberingStyleNameAnswer, $paragraphStyleName);
                        }
                    } else {
                        $section->addText($answer, array('size' => 12, 'name' => $laoFontName, 'color' => '1f2d3d'), ['indent' => 1]);
                    }
                    $section->addTextBreak();
                }
            }
        }
        $section->addText('Summary Assessment Scores', array('size' => 14, 'name' => $laoFontName, 'color' => '1f2d3d', 'bold' => true));
        $section->addText('ຄະແນນ ການສັງລວມ ຜົນການປະເມີນ ຈາກບົດປະເມີນນີ້:', array('size' => 11, 'name' => $laoFontName, 'color' => '1f2d3d'), ['indent' => 0.5]);
        $section->addTextBreak();
        $sectionMultilevelNumberingStyleName = "$multilevelNumberingStyleName-summary";
        $phpWord->addNumberingStyle(
            $sectionMultilevelNumberingStyleName,
            array(
                'type' => 'multilevel',
                'levels' => array(
                    array('format' => 'decimal', 'text' => '%1.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
                    array('format' => 'decimal', 'text' => '%1.', 'left' => 1080, 'hanging' => 360, 'tabPos' => 1080),
                ),
            )
        );
        foreach ($mSections as $sKey => $mSection) {
            $section->addListItem("$mSection->title: {$checkSections[$sKey]->score}", 0, $fontStyleQTitleName, $sectionMultilevelNumberingStyleName, $paragraphStyleName);
        }

        $section->addTextBreak();

        //$section->addListItem($checkSection->title, 0, $fontStyleSectionTitleName, $multilevelNumberingStyleName, $paragraphStyleName);
//        // Adding Text element to the Section having font styled by default...
//        $section->addText(
//            '"Learn from yesterday, live for today, hope for tomorrow. '
//            . 'The important thing is not to stop questioning." '
//            . '(Albert Einstein)'
//        );
//        /*
//         * Note: it's possible to customize font style of the Text element you add in three ways:
//         * - inline;
//         * - using named font style (new font style object will be implicitly created);
//         * - using explicitly created font style object.
//         */
//
//        // Adding Text element with font customized inline...
//
//
//        // Adding Text element with font customized using named font style...
//        $fontStyleName = 'Phetsarath OT';
//        $phpWord->addFontStyle(
//            $fontStyleName,
//            array('name' => 'Saysettha OT', 'size' => 10, 'color' => '1B2232', 'bold' => true)
//        );
//        $section->addText(
//            '"The greatest accomplishment is not in never falling, '
//            . 'but in rising again after you fall." '
//            . '(Vince Lombardi)',
//            $fontStyleName
//        );
//
//        // Adding Text element with font customized using explicitly created font style object...
//        $fontStyle = new PhpWord\Style\Font();
//        $fontStyle->setBold();
//        $fontStyle->setName('Saysettha OT');
//        $fontStyle->setSize(13);
//        $myTextElement = $section->addText('"Believe you can and you\'re halfway there." (Theodor Roosevelt)');
//        $myTextElement->setFontStyle($fontStyle);
//
//        $pStyle = new PhpWord\Style\Paragraph();
//        $pStyle->setAlignment('center');
//        $pStyle->setIndent(10);
//
//        $myTextElement->setParagraphStyle($pStyle);
        return $phpWord;
    }
}
