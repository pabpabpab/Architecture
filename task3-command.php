<?php

// Command

// ==================================================== User ========================================================
class User
{
    protected string $clipboard;

    protected array $commands = [];
    protected int $currentCommandNumber = 0;


    public function run(ICommand $command): void
    {
        $command->execute();
        $this->commands[] = $command;
        $this->currentCommandNumber++;
    }

    public function setClipboard($text)
    {
        $this->clipboard = $text;
    }

    public function getClipboard(): string
    {
        return $this->clipboard;
    }

    public function down(int $levels)
    {
        echo "Отменить $levels операций." . PHP_EOL;
        for ($i = 0; $i < $levels; $i++) {
            if ($this->currentCommandNumber > 0) {
                $this->commands[--$this->currentCommandNumber]->unExecute();
            }
        }
    }

    public function up(int $levels)
    {
        echo "Вернуть $levels операций." . PHP_EOL;
        for ($i = 0; $i < $levels; $i++) {
            if ($this->currentCommandNumber < count($this->commands)) {
                $this->commands[$this->currentCommandNumber++]->execute();
            }
        }
    }
}
// ==================================================== /User =======================================================

// ==================================================== Commands ====================================================

interface ICommand
{
    public function execute();
    public function unExecute();
}

class CopyCommand implements ICommand
{
    protected int $start;
    protected int $length;
    protected Editor $editor;
    protected User $user;

    public function __construct(int $start, int $length, Editor $editor, User $user)
    {
        $this->start = $start;
        $this->length = $length;
        $this->editor = $editor;
        $this->user = $user;
    }

    public function execute()
    {
        $text = $this->editor->copy($this->start, $this->length);
        $this->user->setClipboard($text);
    }
    public function unExecute()
    {
        $this->user->setClipboard('');
    }
}

class CutCommand implements ICommand
{
    protected int $start;
    protected int $length;
    protected string $text;
    protected Editor $editor;
    protected User $user;

    public function __construct(int $start, int $length, Editor $editor, User $user)
    {
        $this->start = $start;
        $this->length = $length;
        $this->editor = $editor;
        $this->user = $user;
    }

    public function execute()
    {
        $this->text = $this->editor->cut($this->start, $this->length);
        $this->user->setClipboard($this->text);
    }
    public function unExecute()
    {
        $this->editor->paste($this->start, $this->text);
        $this->user->setClipboard('');
    }
}

class PasteCommand implements ICommand
{
    protected int $start;
    protected int $length;
    protected string $text;
    protected Editor $editor;
    protected User $user;

    public function __construct(int $start, Editor $editor, User $user)
    {
        $this->start = $start;
        $this->text = $user->getClipboard();
        $this->length = mb_strlen($this->text);
        $this->editor = $editor;
        $this->user = $user;
    }

    public function execute()
    {
        $this->editor->paste($this->start, $this->text);
    }
    public function unExecute()
    {
        $this->editor->cut($this->start, $this->length);
    }
}

// ==================================================== /Commands ====================================================

// ==================================================== Editor =======================================================
class Editor
{
    protected string $content;

    public function copy(int $start, int $length): string
    {
        return mb_substr($this->content, $start, $length);
    }

    public function cut(int $start, int $length): string
    {
        $cutOut = mb_substr($this->content, $start, $length);
        $part1 = mb_substr($this->content, 0, $start);
        $part2 = mb_substr($this->content, $start + $length);
        $this->content = $part1.$part2;
        return $cutOut;
    }

    public function paste(int $start, string $text): void
    {
        $part1 = mb_substr($this->content, 0, $start);
        $part2 = mb_substr($this->content, $start);
        $this->content = $part1.$text.$part2;
    }

    public function openFile($filename)
    {
        if (!file_exists($filename)) {
            $content = "Команда - это поведенческий паттерн проектирования.";
            file_put_contents($filename, $content);
        }
        $this->content = file_get_contents($filename);
    }

    public function saveFile($filename)
    {
        file_put_contents($filename, $this->content);
    }

    public function getContent(): string
    {
        return $this->content;
    }

}
// ==================================================== /Editor ======================================================





$user = new User();
$editor = new Editor();
$editor->openFile('test.txt');


echo "========= Copy =============" . PHP_EOL;

$user->run(new CopyCommand(13, 14, $editor, $user));
echo $user->getClipboard() . PHP_EOL;
$user->down(1); echo PHP_EOL;
echo $user->getClipboard() . PHP_EOL;
$user->up(1); echo PHP_EOL;
echo $user->getClipboard() . PHP_EOL . PHP_EOL;


echo "========= Paste =============" . PHP_EOL;

$user->run(new PasteCommand(27, $editor, $user));
echo $editor->getContent() . PHP_EOL;
$user->down(1); echo PHP_EOL;
echo $editor->getContent() . PHP_EOL;
$user->up(1); echo PHP_EOL;
echo $editor->getContent() . PHP_EOL . PHP_EOL;


echo "========= Cut =============" . PHP_EOL; echo "<br>";

$user->run(new CutCommand(27, 14, $editor, $user));
echo $editor->getContent() . PHP_EOL;
echo $user->getClipboard() . PHP_EOL;

$user->down(1); echo PHP_EOL;
echo $editor->getContent() . PHP_EOL;
echo $user->getClipboard() . PHP_EOL;

$user->up(1); echo PHP_EOL;
echo $editor->getContent() . PHP_EOL;
echo $user->getClipboard() . PHP_EOL . PHP_EOL;


echo "========= 3 Paste =============" . PHP_EOL;

$user->run(new PasteCommand(27, $editor, $user));
$user->run(new PasteCommand(27, $editor, $user));
$user->run(new PasteCommand(27, $editor, $user));
echo $editor->getContent() . PHP_EOL;
echo $user->getClipboard() . PHP_EOL . PHP_EOL;

echo "========= Cancel all =============" . PHP_EOL;

$user->down(6);
echo $editor->getContent() . PHP_EOL;



$editor->saveFile('test.txt');