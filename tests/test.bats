setup() {
  set -eu -o pipefail
  export DIR="$( cd "$( dirname "$BATS_TEST_FILENAME" )" >/dev/null 2>&1 && pwd )/.."
  export TESTDIR=~/tmp/test-ddev-drupal-core-dev
  mkdir -p $TESTDIR
  export PROJNAME=ddev-drupal-core-dev
  export DDEV_NON_INTERACTIVE=true
  ddev delete -Oy ${PROJNAME} >/dev/null 2>&1 || true
  curl -L -o /tmp/drupal.tar.gz https://ftp.drupal.org/files/projects/drupal-11.x-dev.tar.gz
  tar --strip-components 1 -zxf /tmp/drupal.tar.gz -C ${TESTDIR}
  cd "${TESTDIR}"
  ddev config --project-name=${PROJNAME} --upload-dirs=.ddev/tmp
  ddev config --update
  ddev start -y >/dev/null
}

health_checks() {
  ddev exec "curl -s chrome:7900" | grep "noVNC"
  ddev exec "curl -s firefox:7901" | grep "noVNC"
  ddev phpunit core/modules/system/tests/src/FunctionalJavascript/FrameworkTest.php
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
  health_checks
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

