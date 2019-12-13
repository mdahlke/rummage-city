@servers(['localhost' => ['192.168.1.1']])

@task('foo', ['on' => 'localhost', 'confirm' => true])
    ls -la

    @slack('https://hooks.slack.com/services/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')
@endtask


