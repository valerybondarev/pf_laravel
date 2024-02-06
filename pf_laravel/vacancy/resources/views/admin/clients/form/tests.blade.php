@if (!empty($client->id))
    <?php
    /** @var \App\Domain\Client\Entities\Client $client */
    $tests = [];
    foreach ($client->answers as $answer) {
        $tests[$answer->question->test->id][] = $answer;
    }
    ?>
    <div class="row mb-2 mx-2">
        <div class="col-md-12">
            <?php foreach($tests as $answers): ?>
            <h4>Название теста: <?= $answers[0]->question->test->title ?></h4><br>
                <span><strong>Тип пользователя по итогу: </strong><?= $client->getTestResult($answers[0]->question->test)?->title ?></span><br>
                <?php foreach($answers as $index => $answer): ?>
                    <span><strong>Вопрос <?= $index + 1 ?></strong></span>
                    <span><?= $answer->question->question ?></span>
                    <span>Ответ: <?= $answer->title ?>. Тип результата ответа <?= $answer->result->title ?></span>
                <hr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>
@endif
