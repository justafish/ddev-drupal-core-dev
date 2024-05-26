setup() {
  set -eu -o pipefail
  export DIR="$( cd "$( dirname "$BATS_TEST_FILENAME" )" >/dev/null 2>&1 && pwd )/.."
  export TESTDIR=~/tmp/test-ddev-drupal-core-dev
  rm -rf ${TESTDIR}
  mkdir -p ${TESTDIR}
  export PROJNAME=test-ddev-drupal-core-dev
  export DDEV_NON_INTERACTIVE=true
  ddev delete -Oy ${PROJNAME} >/dev/null 2>&1 || true
  curl -L -o /tmp/drupal.tar.gz https://ftp.drupal.org/files/projects/drupal-11.x-dev.tar.gz
  tar --strip-components 1 -zxf /tmp/drupal.tar.gz -C ${TESTDIR}
  cd "${TESTDIR}"
  mv vendor /tmp/vendor.bak
  git config --global user.email "example@example.com"
  git config --global user.name "Example Example"
  git init && git add . >/dev/null && git commit -m "current" >/dev/null
  mv /tmp/vendor.bak vendor
  ddev config --project-name=${PROJNAME} --upload-dirs=.ddev/tmp
  ddev config --update
  ddev start -y >/dev/null
  ddev composer install >/dev/null
}

base_checks() {
  ddev exec "curl -s chrome:7900" | grep "noVNC" >/dev/null
  ddev exec "curl -s firefox:7901" | grep "noVNC" >/dev/null
  ddev phpunit core/tests/Drupal/Tests/Component/Datetime/DateTimePlusTest.php
}

drush_checks() {
  # Make sure there's nothing in the git index before drush install
  git diff --cached --quiet
  ddev drush st
  # Make sure there's nothing after the drush install
  git diff --cached --quiet || (echo "git index has been touched" && exit 2)
  ddev drush si -y --account-pass=admin
}

teardown() {
  set -eu -o pipefail
  cd ${TESTDIR} || ( printf "unable to cd to ${TESTDIR}\n" && exit 1 )
  ddev delete -Oy ${PROJNAME} >/dev/null 2>&1
  [ "${TESTDIR}" != "" ] && rm -rf ${TESTDIR}
}

@test "install from directory" {
  set -eu -o pipefail
  cd ${TESTDIR}
  echo "# ddev get ${DIR} with project ${PROJNAME} in ${TESTDIR} ($(pwd))" >&3
  ddev get ${DIR}
  ddev restart
  base_checks
  drush_checks
}

#TODO: Re-enable release tests after the add-on has a release with DDEV v1.23.0 support
#@test "install from release" {
#  set -eu -o pipefail
#  cd ${TESTDIR} || ( printf "unable to cd to ${TESTDIR}\n" && exit 1 )
#  echo "# ddev get ddev/ddev-addon-template with project ${PROJNAME} in ${TESTDIR} ($(pwd))" >&3
#  ddev get justafish/ddev-drupal-core-dev
#  ddev restart >/dev/null
#  health_checks
#}

