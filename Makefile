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

test:
	@for target in `find . -type f -name "xunit.php"`; do\
	    echo $$target;\
	    $(PHP) $$target;\
	done
.PHONY: test
