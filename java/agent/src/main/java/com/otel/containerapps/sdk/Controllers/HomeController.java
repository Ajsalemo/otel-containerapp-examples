package com.otel.containerapps.sdk.Controllers;

import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RestController;


@RestController
public class HomeController {
    String msg = "otel-sdk-examples-java-sdk";

    @GetMapping("/")
    public String index() {
        return msg;
    }
}
