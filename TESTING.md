# Testing in the PIM

## About

This document aims to help the writing of tests in the PIM. It's the result of training sessions and workshops we had with [Matthias Noback](https://matthiasnoback.nl/) around this topic. This guide applies for both backend and frontend applications.


## Types of tests

Let's remind here the characteristics of each type of tests, the tools we must use from now on and give some concrete examples.

### Specification - describe the behavior of a unit of code

Most of the time, a unit of code is a class.

Characteristics:

- it tests only one class at a time
- it performs no [I/O call](#faq) (in memory only)
- it doesn't require any setup (no fixtures for instance)

Tools:

- backend: PhpSpec
- frontend: Jest & Enzyme

Where:

- backend: `tests/back/Specification`
- frontend: TODO

Examples:

- backend: test the service which calculates the completeness of a product
- frontend: test the dropdown component

### Acceptance - test a business use case or ensure a business rule

Characteristics:

- it tests several classes at the same time
- it uses the business language, which means _Gherkin_ must be used
- it describes a business use case or it ensures a business rule (it's not about UI, CLI or UX, neither about a text we should see)
- it fakes services performing [I/O calls](#faq) (in memory only)

Tools:

- backend: Gherkin through Behat (no Mink, no Selenium)
- frontend: Gherkin through CucumberJS & Puppeteer

Where:

- _Gherkin_: `tests/features`. Same _Gherkin_ files are used for both backend and frontend acceptance tests.
- backend: `tests/back/Acceptance` contains the Behat contexts
- frontend: `tests/front/Acceptance` contains the CucumberJS contexts

Examples:

- backend/frontend: test that the completeness is calculated after having filled in a product value

### Integration - test the integration of a brick with the outside world

Characteristics:

- it has no double (it tests the real classes)
- it may test several classes at the same time
- it tests only services that perform [I/O calls](#faq)

Tools:

- backend: PhpUnit
- frontend: Jest with Puppeteer

Where:

- backend: `tests/back/Integration`
- frontend: `tests/front/Integration`

Examples:

- backend: test a Doctrine repository using MySQL
- frontend: test a fetcher performing HTTP calls

### End to end - test the whole application

Characteristics:

- it tests the application as a whole (the backend and the frontend are tested at the same time)
- it treats the application as a black box
- it has no mock (it tests the real application)
- it can require a complex setup (like a browser and Selenium for instance)
- it tests critical use cases

Tools:

- Behat with Mink and Selenium

Where:

- Gherkin: `tests/features`. Same _Gherkin_ files are used for both end to end and acceptance tests.
- backend: `tests/end-to-end`

Examples:

- test that a user can fill in product values via the UI
- test that an import can be launched via the CLI


## It's all about architecture

### The foundations: ports and adapters




### A slight touch of Onion Architecture

Onion Architecture follows the same principles that ports and adapters regarding the layers segregation. The most important thing is that no external layer should leak into a deeper layer. The main difference is that it introduces a new layer, which means we end up with:

- Domain: it holds the model and all the business logic
- Application: it orchestrates the Domain and Infrastructure layers. It translates and validates the outside world to the Domain. It is the realm of use cases.
- Infrastructure: it talks with the outside world. Typically, it persists domain objects and receives user's inputs. This is where we'll find the repository implementations, the frameworks glue, everything that's related databases, HTTP and all the other ports of the system.


### The relation with the tests

Once you have those layers in mind, it's easy to know what type of test you should write.

Specification:

- it focuses on the Domain layer

Acceptance:

- it focuses on the Application layer

Integration:

- it focuses on the Infrastructure layer
- it tests an Adapter

End to end:

- it crosses over all the layers, from an adapter to another by passing through the Domain layer (for instance: Adapter A -> Application -> Domain -> Application -> Adapter B)

TODO: find an image for that

## Actual vs Expected

### Today's situation

Let's be honest. Today is a mess. Our goal should be to revamp the `tests/legacy` folder. It contains a lot of end to end tests which should be split into acceptance tests and frontend unit tests. For instance, we have some Behat that test a file has been imported through the UI. We should instead have:

- several acceptance tests that check the import use cases
- a very few end to end tests that check the CLI
- frontend unit tests that checks the components of the UI

### The ideal pyramid

![Ideal tests pyramid](/tests_pyramid.png "Ideal tests pyramid")

### How to move towards the ideal pyramid?


## FAQ

> I don't know if I should write a unit, an acceptance, an integration or an end-to-end test. What should I do?

You should refer to the [ports and adapters architecture](#ports-and-adapters-architecture). Ask yourself, where your class is standing regarding the different layers. Then, you can refer to [this section](#the-relation-with-the-tests).

> I'm afraid to write less end to end tests than before. Are you sure it's a good idea?

For sure, end to end tests are a really safe cocoon. They strictly ensure what we have coded works as expected. But you should also remember that they become a burden when they are too numerous. As long as you have many unit and acceptance tests, as well as the necessary integration tests, you're safe. That means all your use cases are covered, and you're able to communicate with the outside world. A few system tests will only ensure that you have correctly glued all the pieces together. They do nothing more. In any case, if you have some doubt, ask the piece of advice of a teammate.

> What is a service that performs I/O calls?

Any service that uses an external system (relatively to your code). Can be considered as external systems: the file system, the system time, any system called via the network, a database or a search engine for instance. That means a Doctrine repository, which communicates with the database, is a service performing I/O calls.

> Testing is really cool, but our CI builds are too long. So I don't want to test anymore. What should I do?

Your frustration is completely understandable. And yes, the path towards short CI builds will be long. But we've achieved the first and the most difficult step, which was to understand how we should test correctly. Now it's just a matter of time and team motivation. A dedicated section explains how we can [improve current situation](#how-to-move-towards-the-ideal-pyramid).

## Resources

> How can I write useful and powerful Gherkin?

[Writing good Gherkin](https://automationpanda.com/2017/01/30/bdd-101-writing-good-gherkin/) by Andrew Knight

[Modelling by Example Workshop](https://fr.slideshare.net/CiaranMcNulty/modelling-by-example-workshop-phpnw-2016) by Ciaran McNulty

[Introducing Modelling by Example](http://stakeholderwhisperer.com/posts/2014/10/introducing-modelling-by-example) by Konstantin Kudryashov

> I want to know more about that ports and adapters thing!

[Hexagonal architecture](http://alistair.cockburn.us/Hexagonal%20architecture), original article by Alistair Cockburn

[Improve Your Software Architecture with Ports and Adapters](https://spin.atomicobject.com/2013/02/23/ports-adapters-software-architecture/) by Tony Baker

[Ports & Adapters Architecture](https://herbertograca.com/2017/09/14/ports-adapters-architecture/) by Herberto Graça

[Ports-And-Adapters / Hexagonal Architecture](http://www.dossier-andreas.net/software_architecture/ports_and_adapters.html)

> I want to know more about the separation layers described in Domain-Driven Design!

