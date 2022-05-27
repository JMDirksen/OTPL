# Important
Make sure the file `otpl.json` is not accessible publicly, it will contain the stored passwords in plain text.

# Docker run
    docker run -d -p 80:80 -e EMAIL=your@email.com jmdirksen/otpl

# Docker build
    git clone https://github.com/JMDirksen/OTPL.git
    cd OTPL
    docker build -t otpl .
    docker run -d -p 80:80 -e EMAIL=your@email.com otpl

# ENV / VOLUME
    ENV EMAIL=admin@domain.com
    ENV EXPIRE_DAYS=7
    ENV PAGE_TITLE="One Time Password Link"
    ENV LOGO=logo.png
    ENV CSS=otpl.css
    ENV JSON=/otpl/otpl.json
    VOLUME /otpl
