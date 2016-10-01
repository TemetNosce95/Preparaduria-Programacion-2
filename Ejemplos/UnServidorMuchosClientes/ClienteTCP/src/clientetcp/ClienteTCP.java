/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package clientetcp;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.Socket;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author temetnosce
 */
public class ClienteTCP {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) throws IOException {
        final Socket sock = new Socket("localhost", 30000);
        BufferedReader buffer = new BufferedReader(new InputStreamReader(System.in));
        
        Thread hiloLectura = new Thread(new Runnable() {
            @Override
            public void run() {
                try {
                    InputStream is = sock.getInputStream();
                    BufferedReader buffer = new BufferedReader(new InputStreamReader(is));
                    
                    while(true){
                        if(is.available() > 0){
                            String incoming = buffer.readLine();
                            System.out.println("Desde el Servidor -> "+incoming);
                        }
                    }
                } catch (IOException ex) {
                    System.err.println("Error en la lectura del socket");
                    System.exit(-1);
                }
            }
        });
        
        hiloLectura.start();
        
        while(true){
            System.out.println("Enviele algo = ");
            String s = buffer.readLine();
            
            if(s.compareTo("close") == 0) break;
            s += "\n";
            sock.getOutputStream().write(s.getBytes());
        }
        
        sock.close();
    }
    
}
