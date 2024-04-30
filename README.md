# LBO - Delivery customer notification
Allocated time: less then 2 hours.

## To get started
### Requirements
- Docker
- Make

### Setup
- Run `make build && make up`
- In terminal: `docker exec -ti test-2024-php-1 /bin/bash`
- Once in the Docker container:
    - Execute the tracking command: `bin/console parcel:tracking MR-D123456789`
    - Execute the functional test: `./vendor/bin/phpunit`

### Fixtures for testing purpose
4 tracking codes are recognized at the moment, 2 for Mondial Relay and the other for Colissimo:

| Tracking code   | Postal Service| Notified customer | Is delivered? |
|-----------------|---|---|---------------|
| SOCO-123456789  | So.Colissimo | j.wayne@soco.com | no            |
| MR-123456789    | MondialRelay | j.does@mondialrelay.com | no            |
| SOCO-D123456789 | So.Colissimo | j.wayne@soco.com | yes           |
| MR-D123456789   | MondialRelay | j.does@mondialrelay.com | yes           |

## Assignment
The aim of this exercise is to demonstrate how you handle the integration of some new functionality into an existing code.

### What to do
Currently, we're offering two shipping methods to our customers that are:
- So.Colissimo
- Mondial Relay

**You have to complete the code that will send notification to the customer once their parcel has been delivered.**

To simplify the exercise we have coded a large part of the feature to give you enough time to design the best way possible.

### Steps
1. Fork the repository on your Github own account, go the `master` branch

2. Complete with your own code the "holes" to complete the feature

    - You can test your command in the container: `bin/console parcel:tracking <trackingCode>`

    - The console display should be for Mondial Relay:

        ```
         bin/console parcel:tracking MR-D123456789
         [John Doe <j.doe@mondialrelay.com>] New Mondial Relay parcel "MR-D123456789" received.
      ```

      And for So.Colissimo:

         ```
         bin/console parcel:tracking MR-D123456789
         [John Wayne <j.wayne@soco.com>] New SoColissimo parcel "SOCO-D123456789" received.
         ```

3. The PHPUnit test suite must pass without error
    - You can trigger the test in the container: `./vendor/bin/phpunit`

**BONUS: Add another postal service, for instance "Chronopost"**

4. Once all done:
- Open a PR with your branch and send the PR link to Marine (m.krol@laboutiqueofficielle.com)
- Make an archive of your code (all the project without the `vendor`) and send it to Marine (m.krol@laboutiqueofficielle.com)

*Be aware that the goal is to complete this test in less than 2 hours, the sending time of the first mail is authoritative.*

### Important
- Do not forget to handle all potential exceptions in your code
- We expect you to design and build some SOLID architecture ( https://www.baeldung.com/solid-principles )
- Always try to be consistent in your commits
- You can use any of Symfony or PHP 8.3 features
