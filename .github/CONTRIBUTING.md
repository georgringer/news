# Contributing

When contributing to this repository, please first discuss the change you wish to make via issue,
email, or any other method with the owners of this repository before making a change.

## Getting Started

* Make sure you have a [GitHub account](https://github.com/signup/free)
* Submit a ticket for your [issue](https://github.com/georgringer/news/issues), assuming one does not already exist.
  * Clearly describe the issue including steps to reproduce when it is a bug.
* Fork the repository on GitHub

## Making Changes

* Create a topic branch from where you want to base your work.
  * This is usually the master branch.
  * Only target release branches if you are certain your fix must be on that
    branch.
  * To quickly create a topic branch based on master; `git checkout -b
    fix/master/my_contribution master`. Please avoid working directly on the
    `master` branch.
* Make commits of logical units.
* Use `./php-cs-fixer fix --config-file Build/.php_cs` to make sure the code is formatted correctly.
* Make sure your commit messages are in the proper format. Use either `[TASK]`, `[FEATURE]`, `[BUGFIX]` or `[DOC]`

````
    [TASK] Make the example in CONTRIBUTING imperative and concrete

    The first line is a real life imperative statement.
    The body describes the behavior without the patch,
    why this is a problem, and how the patch fixes the problem when applied.

    Resolves: #123
````

* Make sure you have added the necessary tests for your changes.
* Run _all_ the tests to assure nothing else was accidentally broken. However travis will do that for you as well.

## Making Trivial Changes

For changes of a trivial nature, it is not always necessary to create a new issue.

## Additional resources

* [Rendered documentation](https://docs.typo3.org/typo3cms/extensions/news/)
* [How to Write a Git Commit Message](http://chris.beams.io/posts/git-commit/)


## Contributor Code of Conduct

As contributors and maintainers of this project, and in the interest of fostering an open and
welcoming community, we pledge to respect all people who contribute through reporting issues,
posting feature requests, updating documentation, submitting pull requests or patches, and other
activities.

We are committed to making participation in this project a harassment-free experience for everyone,
regardless of level of experience, gender, gender identity and expression, sexual orientation,
disability, personal appearance, body size, race, ethnicity, age, religion, or nationality.

Examples of unacceptable behavior by participants include:

* The use of sexualized language or imagery
* Personal attacks
* Trolling or insulting/derogatory comments
* Public or private harassment
* Publishing other's private information, such as physical or electronic addresses, without explicit
  permission
* Other unethical or unprofessional conduct.

Project maintainers have the right and responsibility to remove, edit, or reject comments, commits,
code, wiki edits, issues, and other contributions that are not aligned to this Code of Conduct. By
adopting this Code of Conduct, project maintainers commit themselves to fairly and consistently
applying these principles to every aspect of managing this project. Project maintainers who do not
follow or enforce the Code of Conduct may be permanently removed from the project team.

This code of conduct applies both within project spaces and in public spaces when an individual is representing the project or its community.

Instances of abusive, harassing, or otherwise unacceptable behavior may be reported by opening an issue or contacting one or more of the project maintainers.

This Code of Conduct is adapted from the [Contributor Covenant](http://contributor-covenant.org),
version 1.2.0, available at [http://contributor-covenant.org/version/1/2/0/](http://contributor-covenant.org/version/1/2/0/)
