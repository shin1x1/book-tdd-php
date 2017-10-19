ifdef TRAVIS
    PHP := php
else
    PHP := docker-compose run php php
endif

test:
	@for target in `find . -type f -name "xunit.php"`; do\
	    echo $$target;\
	    $(PHP) $$target;\
	done
.PHONY: test
