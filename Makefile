ifdef TRAVIS
    PHP := php
    COMPOSER := composer
else
    PHP := docker-compose run php php
    COMPOSER := docker-compose run composer composer
endif

all: install test
.PHONY: all

install:
	$(COMPOSER) install
.PHONY: install

test: test_part1 test_part2
.PHONY: test

test_part1:
	$(PHP) ./vendor/bin/phpunit
.PHONY: test_part1

test_part2:
	@for target in `find . -type f -name "xunit.php" | sort`; do\
	    echo $$target;\
	    $(PHP) $$target;\
	done
.PHONY: test_part2
